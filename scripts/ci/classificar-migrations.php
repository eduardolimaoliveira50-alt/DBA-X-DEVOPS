<?php

/**
 * Guardrail de PR para migrations (seções 2.2.2 e 2.3.1 do trabalho).
 *
 * 1. Imutabilidade: migration que já existe na branch de destino não pode ser alterada, removida
 *    nem renomeada (é o equivalente ao checksum do Flyway: o que já rodou em produção não muda).
 * 2. Classificação de risco das migrations novas (só o corpo do up()):
 *    - CRÍTICA (alteração de tipo/obrigatoriedade, rename, DROP, SQL bruto): tem que estar em
 *      database/migrations/criticas/, caminho com revisor obrigatório do DBA na política da main.
 *      Fora dessa pasta, o pipeline reprova o PR.
 *    - MODERADA (índice/unique/foreign em tabela existente): passa, com aviso para validar em staging.
 *    - BAIXO RISCO (tabela nova, coluna anulável): revisão por pares basta.
 *
 * A regra é por padrão de código e por caminho, não por análise semântica do SQL (limitação
 * reconhecida no próprio trabalho).
 *
 * uso: php scripts/ci/classificar-migrations.php <ref-base>   (ex.: origin/main ou HEAD~1)
 */

$base = $argv[1] ?? 'origin/main';
$pastaCriticas = 'database/migrations/criticas/';

function git(string $args): array
{
    exec('git ' . $args . ' 2>&1', $out, $code);
    if ($code !== 0) {
        fwrite(STDERR, "falha em git {$args}:\n" . implode("\n", $out) . "\n");
        exit(2);
    }
    return array_values(array_filter($out, fn ($l) => trim($l) !== ''));
}

function anotar(string $tipo, string $msg, ?string $arquivo = null): void
{
    // Anotações do Azure Pipelines (aparecem no resumo da execução e no PR).
    $onde = $arquivo ? ";sourcepath={$arquivo}" : '';
    echo "##vso[task.logissue type={$tipo}{$onde}]{$msg}\n";
}

function corpoDoUp(string $codigo): string
{
    if (! preg_match('/function\s+up\s*\([^)]*\)[^{]*\{/', $codigo, $m, PREG_OFFSET_CAPTURE)) {
        return $codigo; // sem up() reconhecível: analisa o arquivo inteiro (mais conservador)
    }
    $inicio = $m[0][1] + strlen($m[0][0]);
    $nivel = 1;
    for ($i = $inicio, $n = strlen($codigo); $i < $n; $i++) {
        if ($codigo[$i] === '{') {
            $nivel++;
        } elseif ($codigo[$i] === '}' && --$nivel === 0) {
            return substr($codigo, $inicio, $i - $inicio);
        }
    }
    return substr($codigo, $inicio);
}

$criticos = [
    'dropColumn/dropColumns' => '/->dropColumns?\s*\(/',
    'dropTable/dropIfExists' => '/Schema::(drop|dropIfExists|dropColumns)\s*\(/',
    'drop de índice/chave' => '/->drop(Primary|Foreign|Unique|Index|SoftDeletes|Timestamps|Morphs)\s*\(/',
    'renameColumn/rename' => '/(->renameColumn\s*\(|Schema::rename\s*\(|->rename\s*\()/',
    'alteração de coluna (->change())' => '/->change\s*\(\s*\)/',
    'SQL bruto (DB::statement/unprepared)' => '/DB::(statement|unprepared)\s*\(/',
    'TRUNCATE/DELETE em massa' => '/->(truncate|delete)\s*\(\s*\)/',
];
$moderados = [
    'índice/unique em tabela existente' => '/->(index|unique|fullText|spatialIndex)\s*\(/',
    'chave estrangeira' => '/->(foreign|foreignId|constrained)\s*\(/',
];

$erros = 0;
$resumo = ["## Classificação das migrations (base: `{$base}`)", ''];

// 1. Imutabilidade
foreach (git("diff --name-status --no-renames {$base}...HEAD -- database/migrations") as $linha) {
    [$status, $arquivo] = preg_split('/\s+/', $linha, 2);
    if (! str_ends_with($arquivo, '.php')) {
        continue;
    }
    if ($status !== 'A') {
        $erros++;
        $acao = ['M' => 'alterada', 'D' => 'removida'][$status[0]] ?? "modificada ({$status})";
        anotar('error', "Migration já existente na branch de destino foi {$acao}: {$arquivo}. Migrations aplicadas são imutáveis; crie uma migration nova.", $arquivo);
        $resumo[] = "- ❌ **{$arquivo}**: migration existente foi {$acao} (imutabilidade)";
    }
}

// 2. Classificação das novas
$novas = array_filter(
    git("diff --name-only --diff-filter=A {$base}...HEAD -- database/migrations"),
    fn ($f) => str_ends_with($f, '.php')
);

if (! $novas) {
    $resumo[] = '- Nenhuma migration nova neste PR.';
}

foreach ($novas as $arquivo) {
    $up = corpoDoUp(file_get_contents($arquivo));
    $achadosCriticos = array_keys(array_filter($criticos, fn ($re) => preg_match($re, $up)));
    $ehTabelaExistente = (bool) preg_match('/Schema::table\s*\(/', $up);
    $achadosModerados = $ehTabelaExistente
        ? array_keys(array_filter($moderados, fn ($re) => preg_match($re, $up)))
        : [];
    $naPastaCritica = str_starts_with($arquivo, $pastaCriticas);

    if ($achadosCriticos) {
        $motivo = implode(', ', $achadosCriticos);
        if ($naPastaCritica) {
            anotar('warning', "Migration CRÍTICA ({$motivo}): exige aprovação do DBA Modernizado (revisor obrigatório da pasta criticas/).", $arquivo);
            $resumo[] = "- ⚠️ **{$arquivo}**: CRÍTICA ({$motivo}) → aprovação obrigatória do DBA";
        } else {
            $erros++;
            anotar('error', "Migration CRÍTICA ({$motivo}) fora de {$pastaCriticas}. Mova para essa pasta (revisão obrigatória do DBA) ou use o padrão Expand-Contract.", $arquivo);
            $resumo[] = "- ❌ **{$arquivo}**: CRÍTICA ({$motivo}) fora de `{$pastaCriticas}` → PR bloqueado";
        }
    } elseif ($achadosModerados) {
        $motivo = implode(', ', $achadosModerados);
        anotar('warning', "Migration de risco MODERADO ({$motivo}): validar plano de execução em staging; DBA consultado em tabelas de alta cardinalidade.", $arquivo);
        $resumo[] = "- 🟡 **{$arquivo}**: MODERADA ({$motivo}) → revisão por pares + validação em staging";
    } else {
        if ($naPastaCritica) {
            anotar('warning', 'Migration sem operação crítica dentro de criticas/: o DBA vai revisar sem necessidade.', $arquivo);
        }
        $resumo[] = "- ✅ **{$arquivo}**: BAIXO RISCO (aditiva) → revisão por pares";
    }
}

$resumo[] = '';
$resumo[] = $erros ? "**Resultado: {$erros} bloqueio(s).**" : '**Resultado: nenhum bloqueio.**';
$texto = implode("\n", $resumo) . "\n";
echo "\n" . $texto;

// Resumo em Markdown anexado à execução (aba "Extensions" do pipeline). Não usa armazenamento pago.
$dir = getenv('AGENT_TEMPDIRECTORY');
if ($dir) {
    $arq = $dir . DIRECTORY_SEPARATOR . 'classificacao-migrations.md';
    file_put_contents($arq, $texto);
    echo "##vso[task.uploadsummary]{$arq}\n";
}

exit($erros ? 1 : 0);
