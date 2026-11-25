<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Massa fictícia com o formato da produção (o "sistema antigo" migrado para cá). Usada no ensaio em staging do
 * pipeline e no banco publicado. Reproduz de propósito o que existe em bancos reais e que o banco vazio dos
 * testes não mostra:
 *
 *  - produtos com CÓDIGO REPETIDO (a carga da planilha antiga duplicou itens);
 *  - fornecedores SEM CNPJ (cadastro antigo não exigia);
 *  - MOVIMENTAÇÕES em volume (centenas de milhares de linhas): DDL que trava a tabela vira indisponibilidade.
 *
 * Determinística (setseed): toda execução gera os mesmos dados. Volume: ALMOX_MOVIMENTACOES (padrão 300 mil).
 *
 * Na produção real, essa carga entrou no ESQUEMA INICIAL, antes das migrations seguintes. O ensaio em staging
 * reproduz essa ordem (scripts/ci/ensaio-staging.sh). Num banco novo em que as regras já existem (UNIQUE no
 * código, CNPJ obrigatório), a massa respeita as regras em vez de quebrar.
 */
class MassaProducaoSeeder extends Seeder
{
    public const PRODUTOS = 4000;

    public const DUPLICADOS = 30;

    public const FORNECEDORES = 800;

    /** um a cada 12 fornecedores veio sem CNPJ */
    public const SEM_CNPJ_A_CADA = 12;

    private const CATEGORIAS = [
        'Expediente', 'Limpeza', 'Informática', 'Copa e cozinha', 'EPI', 'Elétrica',
        'Hidráulica', 'Ferramentas', 'Higiene', 'Embalagens', 'Mobiliário', 'Laboratório',
    ];

    private const ITENS = [
        ['Papel A4 75g', 'RESMA', 0], ['Caneta esferográfica azul', 'CX', 0], ['Grampeador de mesa', 'UN', 0],
        ['Pasta suspensa', 'CX', 0], ['Envelope pardo A4', 'PCT', 0], ['Detergente neutro 500ml', 'UN', 1],
        ['Saco de lixo 100L', 'PCT', 1], ['Desinfetante 2L', 'UN', 1], ['Pano multiuso', 'PCT', 1],
        ['Mouse óptico USB', 'UN', 2], ['Teclado ABNT2', 'UN', 2], ['Cabo de rede Cat6 2m', 'UN', 2],
        ['Toner preto', 'UN', 2], ['Copo descartável 200ml', 'PCT', 3], ['Café em pó 500g', 'PCT', 3],
        ['Açúcar 1kg', 'KG', 3], ['Luva nitrílica', 'CX', 4], ['Máscara PFF2', 'CX', 4],
        ['Óculos de proteção', 'UN', 4], ['Lâmpada LED 12W', 'UN', 5], ['Disjuntor 20A', 'UN', 5],
        ['Fita isolante', 'UN', 5], ['Registro de gaveta 3/4', 'UN', 6], ['Torneira de parede', 'UN', 6],
        ['Chave de fenda', 'UN', 7], ['Trena 5m', 'UN', 7], ['Papel higiênico rolão', 'PCT', 8],
        ['Sabonete líquido 5L', 'UN', 8], ['Caixa de papelão', 'UN', 9], ['Fita adesiva larga', 'UN', 9],
        ['Cadeira fixa', 'UN', 10], ['Gaveteiro', 'UN', 10], ['Tubo de ensaio', 'CX', 11], ['Álcool 70% 1L', 'L', 11],
    ];

    private const EMPRESAS = ['Distribuidora', 'Comercial', 'Atacadista', 'Suprimentos', 'Papelaria', 'Materiais', 'Indústria', 'Representações'];

    private const NOMES = ['Pantanal', 'Cuiabá', 'Chapada', 'Araguaia', 'Cerrado', 'Rondon', 'Guaporé', 'Xingu', 'Ipê', 'Buriti'];

    public function run(): void
    {
        if (DB::table('produtos')->exists()) {
            $this->command?->info('Massa de produção: o banco já tem produtos, nada a carregar.');

            return;
        }

        $movimentacoes = (int) env('ALMOX_MOVIMENTACOES', 300000);

        // tudo ou nada: uma carga interrompida (ex.: contêiner reiniciado) não deixa produtos sem movimentações
        DB::transaction(function () use ($movimentacoes) {
            DB::table('categorias')->insertOrIgnore(array_map(
                fn ($nome) => ['nome' => $nome, 'created_at' => now(), 'updated_at' => now()], self::CATEGORIAS));
            $categorias = DB::table('categorias')->orderBy('id')->pluck('id', 'nome');

            $this->fornecedores();
            $this->produtos($categorias->values()->all());

            DB::connection()->getDriverName() === 'pgsql'
                ? $this->movimentacoesPostgres($movimentacoes)
                : $this->movimentacoesSimples(min($movimentacoes, 2000));
        });

        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement('ANALYZE produtos, fornecedores, movimentacoes');
        }

        $this->command?->info(sprintf('Massa de produção: %d produtos (%d códigos repetidos), %d fornecedores (%d sem CNPJ), %d movimentações.',
            self::PRODUTOS + self::DUPLICADOS, self::DUPLICADOS, self::FORNECEDORES,
            intdiv(self::FORNECEDORES, self::SEM_CNPJ_A_CADA), DB::table('movimentacoes')->count()));
    }

    private function fornecedores(): void
    {
        $cnpjObrigatorio = DB::connection()->getDriverName() === 'pgsql'
            && DB::table('pg_constraint')->where('conname', 'fornecedores_cnpj_obrigatorio')->exists();
        $linhas = [];
        for ($n = 1; $n <= self::FORNECEDORES; $n++) {
            $linhas[] = [
                'razao_social' => sprintf('%s %s %03d Ltda', self::EMPRESAS[$n % 8], self::NOMES[intdiv($n, 8) % 10], $n),
                'cnpj' => $n % self::SEM_CNPJ_A_CADA === 0 && ! $cnpjObrigatorio ? null : self::cnpj($n),
                'email' => $n % 5 === 0 ? null : sprintf('vendas%03d@fornecedor.test', $n),
                'telefone' => sprintf('6533%06d', $n),
                'created_at' => now()->subDays(900 - $n),
                'updated_at' => now()->subDays(900 - $n),
            ];
        }
        foreach (array_chunk($linhas, 500) as $lote) {
            DB::table('fornecedores')->insert($lote);
        }
    }

    /** @param  list<int>  $categorias */
    private function produtos(array $categorias): void
    {
        $linhas = [];
        for ($n = 1; $n <= self::PRODUTOS; $n++) {
            [$nome, $unidade, $categoria] = self::ITENS[$n % count(self::ITENS)];
            $linhas[] = [
                'codigo' => sprintf('MAT-%05d', $n),
                'nome' => sprintf('%s – modelo %03d', $nome, intdiv($n, count(self::ITENS)) + 1),
                'categoria_id' => $categorias[$categoria],
                'unidade_medida' => $unidade,
                'estoque_minimo' => 10 + ($n * 7) % 70,
                'saldo' => 0,
                'ativo' => $n % 97 !== 0,
                'created_at' => now()->subDays(800),
                'updated_at' => now()->subDays(800),
            ];
        }
        // a carga do sistema antigo duplicou alguns itens: mesmo código, outro cadastro (com movimentações próprias)
        $codigoUnico = collect(Schema::getIndexes('produtos'))->contains(fn ($i) => $i['unique'] && $i['columns'] === ['codigo']);
        for ($d = 1; $d <= ($codigoUnico ? 0 : self::DUPLICADOS); $d++) {
            $original = $linhas[($d * 131) % self::PRODUTOS];
            $linhas[] = ['nome' => $original['nome'].' (importado)', 'created_at' => now()->subDays(700), 'updated_at' => now()->subDays(700)] + $original;
        }
        foreach (array_chunk($linhas, 500) as $lote) {
            DB::table('produtos')->insert($lote);
        }
    }

    private function movimentacoesPostgres(int $total): void
    {
        $usuario = User::query()->where('email', 'almoxarife@almoxarifado.test')->value('id');

        DB::statement('SELECT setseed(0.2026)');
        DB::statement(<<<'SQL'
            INSERT INTO movimentacoes (produto_id, fornecedor_id, user_id, tipo, quantidade, documento, created_at, updated_at)
            SELECT p.ids[1 + floor(random() * array_length(p.ids, 1))::int],
                   CASE WHEN m.entrada THEN f.ids[1 + floor(random() * array_length(f.ids, 1))::int] END,
                   ?,
                   CASE WHEN m.entrada THEN 'entrada' ELSE 'saida' END,
                   CASE WHEN m.entrada THEN 10 + floor(random() * 50)::int ELSE 1 + floor(random() * 15)::int END,
                   CASE WHEN m.entrada THEN 'NF ' || (100000 + g) ELSE 'REQ ' || (500000 + g) END,
                   m.quando, m.quando
              FROM generate_series(1, ?) AS g
             CROSS JOIN (SELECT array_agg(id ORDER BY id) AS ids FROM produtos) AS p
             CROSS JOIN (SELECT array_agg(id ORDER BY id) AS ids FROM fornecedores) AS f
             CROSS JOIN LATERAL (SELECT g % 3 <> 0 AS entrada,
                                        date_trunc('minute', now() - random() * interval '365 days' + (g * interval '0 second')) AS quando) AS m
            SQL, [$usuario, $total]);

        $this->recalcularSaldos();
    }

    private function movimentacoesSimples(int $total): void
    {
        $produtos = DB::table('produtos')->pluck('id')->all();
        $fornecedores = DB::table('fornecedores')->pluck('id')->all();
        mt_srand(2026);
        $linhas = [];
        for ($g = 1; $g <= $total; $g++) {
            $entrada = $g % 3 !== 0;
            $quando = now()->subMinutes(mt_rand(0, 525600));
            $linhas[] = [
                'produto_id' => $produtos[mt_rand(0, count($produtos) - 1)],
                'fornecedor_id' => $entrada ? $fornecedores[mt_rand(0, count($fornecedores) - 1)] : null,
                'tipo' => $entrada ? 'entrada' : 'saida',
                'quantidade' => $entrada ? mt_rand(10, 59) : mt_rand(1, 15),
                'created_at' => $quando,
                'updated_at' => $quando,
            ];
        }
        foreach (array_chunk($linhas, 500) as $lote) {
            DB::table('movimentacoes')->insert($lote);
        }
        $this->recalcularSaldos();
    }

    private function recalcularSaldos(): void
    {
        DB::statement(<<<'SQL'
            UPDATE produtos SET saldo = m.soma
              FROM (SELECT produto_id, SUM(CASE WHEN tipo = 'entrada' THEN quantidade ELSE -quantidade END) AS soma
                      FROM movimentacoes GROUP BY produto_id) AS m
             WHERE m.produto_id = produtos.id
            SQL);
        // alguns itens abaixo do mínimo, para a tela inicial ter o que mostrar
        DB::statement('UPDATE produtos SET estoque_minimo = saldo + 25 WHERE id % 37 = 0');
    }

    /** CNPJ válido (dígitos verificadores corretos) e determinístico a partir de um número. */
    public static function cnpj(int $n): string
    {
        $base = str_pad((string) (11222333 + $n * 7919), 8, '0', STR_PAD_LEFT).'0001';
        foreach ([[5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2], [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2]] as $pesos) {
            $soma = 0;
            foreach ($pesos as $i => $peso) {
                $soma += (int) $base[$i] * $peso;
            }
            $resto = $soma % 11;
            $base .= $resto < 2 ? '0' : (string) (11 - $resto);
        }

        return substr($base, 0, 14);
    }
}
