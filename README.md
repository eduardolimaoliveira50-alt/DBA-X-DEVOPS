# Almoxarifado

Sistema administrativo de **gestão de materiais e estoque** (produtos, fornecedores, entradas e saídas),
em Laravel 12 + PostgreSQL, usado para demonstrar na prática a visão **DBA Pragmático + Modernizado**
do trabalho *DBA versus DevOps* (IFMT, Administração de Banco de Dados, 2026/1).

> **A ideia:** o banco é quase todo gerenciado por **migrations** versionadas no Git e aplicadas pelo
> pipeline. O DBA **cria o banco uma vez** (contas, permissões) e depois **só atua quando é realmente
> necessário**: quando o pipeline prova, com dados no formato da produção, que uma mudança não é segura.

## O fluxo

```
Task na Sprint (Boards) -> branch -> Pull Request (work item vinculado)
   -> pipeline do PR: guardrail -> testes (banco vazio) -> ENSAIO EM STAGING (dados de produção)
        verde: revisão por pares e merge                      (o DBA nem é chamado)
        vermelho: o log diz por quê -> o DBA intervém -> push -> rerun verde -> aprovação do DBA -> merge
   -> pipeline da main: mesmo CI + backup e restauração cronometrada -> publicação no Render
```

| Quem | Faz |
|---|---|
| **DBA** (uma vez) | `scripts/dba/criar-banco.sh`: banco, conta das migrations (`almox_migrator`) e conta da aplicação só com DML (`almox_app`) |
| **Desenvolvedor** | migrations + código no mesmo PR |
| **Pipeline** | guardrail, testes, ensaio em staging, análise de bloqueio, backup/restauração, publicação |
| **DBA** (quando precisa) | só quando o ensaio reprova ou quando a migration está em `database/migrations/criticas/` (revisor obrigatório) |

## Por que o ensaio em staging

Banco vazio aceita qualquer migration. Os problemas reais só aparecem com os dados da produção, e o
`MassaProducaoSeeder` reproduz os de um sistema herdado:

| Na produção | Quebra o quê |
|---|---|
| 30 produtos com **código repetido** (carga da planilha antiga) | qualquer `unique('codigo')` |
| 66 fornecedores **sem CNPJ** (cadastro antigo não exigia) | tornar `cnpj` obrigatório |
| **300 mil movimentações** | índice criado sem `CONCURRENTLY` trava as gravações |

O ensaio leva um PostgreSQL ao estado da versão em produção, carrega essa massa, analisa o SQL pendente
com o **tamanho real das tabelas** (`scripts/ci/riscos-de-bloqueio.php`) e aplica as migrations de verdade.

## Estrutura

```
app/                                 domínio (Produto, Fornecedor, Movimentacao, Estoque)
database/migrations/                 esquema (baixo risco: revisão por pares)
database/migrations/criticas/        risco alto: revisor obrigatório DBA Modernizado
database/seeders/MassaProducaoSeeder massa fictícia com o formato da produção
scripts/dba/criar-banco.sh           a parte do DBA (uma vez por ambiente)
scripts/ci/classificar-migrations.php guardrail por padrão de código e caminho
scripts/ci/ensaio-staging.sh         ensaio com dados de produção (+ backup/restauração na main)
scripts/ci/riscos-de-bloqueio.php    análise de lock com o tamanho real das tabelas
scripts/cd/publicar-render.sh        espelho GitHub -> Render -> confere a versão publicada
azure-pipelines-pr.yml               validação de PR (política de build da main)
azure-pipelines-main.yml             CI + publicação a cada merge
docs/roteiro-demonstracao.md         passo a passo da apresentação (cenários)
```

## Rodar localmente

```bash
composer install && npm ci && npm run build
cp .env.example .env && php artisan key:generate
# PostgreSQL local: sudo -u postgres env ALMOX_MIGRATOR_PASSWORD=... ALMOX_APP_PASSWORD=... bash scripts/dba/criar-banco.sh almoxarifado
DB_USERNAME=almox_migrator DB_PASSWORD=... php artisan migrate --seed
php artisan serve
```

Contas de demonstração (dados fictícios): `admin@almoxarifado.test`, `almoxarife@almoxarifado.test`,
`consulta@almoxarifado.test`, senha `almox2026`.

## Custo

Zero: Azure DevOps gratuito (Boards, Repos, políticas, 1 job Microsoft-hosted, 1.800 min/mês), nenhum
recurso do Azure; publicação no Render **free** (web + PostgreSQL free) a partir de um espelho no GitHub.
