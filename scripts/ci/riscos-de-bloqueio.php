<?php

/**
 * Análise de bloqueio (lock) do SQL pendente, com o TAMANHO REAL das tabelas no staging.
 *
 * No PostgreSQL, várias operações de DDL seguram um lock que impede gravações (ou até leituras) na tabela
 * enquanto rodam. Em tabela pequena isso dura milissegundos; em tabela grande vira indisponibilidade.
 * A regra abaixo é a mesma que times grandes adotam (ex.: os "migration helpers" do GitLab exigem
 * add_concurrent_index acima de um tamanho de tabela) e que o Squawk aponta:
 *
 *   tabela com mais de LIMITE linhas (padrão 100 mil):
 *   - CREATE INDEX / UNIQUE sem CONCURRENTLY   -> bloqueia INSERT/UPDATE/DELETE durante a criação
 *   - ADD CONSTRAINT CHECK/FOREIGN KEY sem NOT VALID -> varre a tabela inteira segurando o lock
 *   - ALTER COLUMN ... SET NOT NULL            -> varre a tabela inteira com ACCESS EXCLUSIVE
 *   - ALTER COLUMN ... TYPE                    -> pode reescrever a tabela inteira com ACCESS EXCLUSIVE
 *
 * uso: php scripts/ci/riscos-de-bloqueio.php <pendente.sql> <tamanhos.txt> <limite> <saida.md>
 *      tamanhos.txt: "tabela linhas" por linha (de pg_class.reltuples, depois do ANALYZE)
 */

[$_, $arquivoSql, $arquivoTamanhos, $limite, $saida] = $argv + [null, null, null, 100000, 'php://stdout'];
$limite = (int) $limite;

$tamanhos = [];
foreach (file($arquivoTamanhos, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $linha) {
    [$tabela, $linhas] = preg_split('/\s+/', trim($linha));
    $tamanhos[$tabela] = (int) $linhas;
}

$sql = array_filter(array_map('trim', file($arquivoSql, FILE_IGNORE_NEW_LINES)));
$regras = [
    'CREATE INDEX sem CONCURRENTLY' => [
        '/^create\s+(unique\s+)?index\s+(?!concurrently)(?:if\s+not\s+exists\s+)?"?\w+"?\s+on\s+"?(?<t>\w+)"?/i',
        'bloqueia INSERT/UPDATE/DELETE em %s durante toda a criação do índice',
        'CREATE INDEX CONCURRENTLY (fora de transação: public $withinTransaction = false)',
    ],
    'UNIQUE via ADD CONSTRAINT' => [
        '/^alter\s+table\s+"?(?<t>\w+)"?\s+add\s+constraint\s+"?\w+"?\s+unique/i',
        'cria o índice único segurando lock de escrita em %s',
        'CREATE UNIQUE INDEX CONCURRENTLY e depois ADD CONSTRAINT ... USING INDEX',
    ],
    'CHECK/FOREIGN KEY sem NOT VALID' => [
        '/^alter\s+table\s+"?(?<t>\w+)"?\s+add\s+constraint\s+"?\w+"?\s+(check|foreign\s+key)(?!.*not\s+valid)/i',
        'varre %s inteira para validar, com a tabela travada',
        'ADD CONSTRAINT ... NOT VALID e, depois, VALIDATE CONSTRAINT',
    ],
    'SET NOT NULL' => [
        '/^alter\s+table\s+"?(?<t>\w+)"?\s+.*alter\s+column\s+"?\w+"?\s+set\s+not\s+null/i',
        'varre %s inteira com ACCESS EXCLUSIVE (nem leitura passa)',
        'CHECK (coluna IS NOT NULL) NOT VALID + VALIDATE; depois SET NOT NULL usa a restrição validada',
    ],
    'ALTER COLUMN TYPE' => [
        '/^alter\s+table\s+"?(?<t>\w+)"?\s+.*alter\s+column\s+"?\w+"?\s+type\s/i',
        'pode reescrever %s inteira com ACCESS EXCLUSIVE',
        'coluna nova + backfill em lotes (Expand-Contract)',
    ],
];

$bloqueios = 0;
$linhasMd = ['## Análise de bloqueio (tamanho real das tabelas no staging)', '',
    "Limite para \"tabela grande\": **".number_format($limite, 0, ',', '.').' linhas**.', ''];
$achados = [];

foreach ($sql as $comando) {
    foreach ($regras as $nome => [$re, $efeito, $alternativa]) {
        if (! preg_match($re, $comando, $m)) {
            continue;
        }
        $tabela = $m['t'];
        $linhas = $tamanhos[$tabela] ?? 0;
        $grande = $linhas > $limite;
        $achados[] = [$grande, $nome, $tabela, $linhas, sprintf($efeito, $tabela), $alternativa, $comando];
        if ($grande) {
            $bloqueios++;
            echo "##vso[task.logissue type=error]Risco de indisponibilidade: {$nome} em {$tabela} (~".number_format($linhas, 0, ',', '.')." linhas) ".sprintf($efeito, $tabela).". Alternativa: {$alternativa}. Intervenção do DBA necessária.\n";
        }
    }
}

if (! $achados) {
    $linhasMd[] = '- ✅ Nenhuma operação que segure lock de escrita em tabela existente.';
} else {
    $linhasMd[] = '| | Operação | Tabela | Linhas | Efeito | Como fazer sem travar |';
    $linhasMd[] = '|---|---|---|---:|---|---|';
    foreach ($achados as [$grande, $nome, $tabela, $linhas, $efeito, $alternativa]) {
        $linhasMd[] = sprintf('| %s | %s | `%s` | %s | %s | %s |', $grande ? '❌' : '✅ tabela pequena', $nome, $tabela,
            number_format($linhas, 0, ',', '.'), $efeito, $grande ? $alternativa : '—');
    }
}
$linhasMd[] = '';

file_put_contents($saida, implode("\n", $linhasMd)."\n", FILE_APPEND);
echo implode("\n", $linhasMd)."\n";
exit($bloqueios ? 1 : 0);
