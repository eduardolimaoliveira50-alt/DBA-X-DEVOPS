# lab-dba: DBA versus DevOps na prática

Laboratório reproduzível do trabalho **"DBA versus DevOps: a sinergia entre o Desenvolvedor Pragmático
e o DBA Modernizado"** (IFMT, Administração de Banco de Dados, 2026/2). Implementa o roteiro da seção
2.3.4: Laravel 12 + PostgreSQL, evolução de esquema pelo padrão **Expand-Contract**, testes de integração
contra o SGBD real e **aprovação proporcional ao risco** por meio de Pull Requests e políticas de branch.

Todos os dados são fictícios. Não há servidor nem banco permanente: o PostgreSQL de testes e a
"produção do laboratório" nascem e morrem dentro do agente do pipeline.

## Como a governança do trabalho virou configuração

| Trabalho (seção) | Onde está no projeto |
|---|---|
| Proposição por branch + PR (2.3.1, passo 1) | `main` protegida: nada entra sem PR |
| Verificação automática no PR (2.3.1, passo 2; 2.2.2) | `azure-pipelines-pr.yml` (validação obrigatória do PR) |
| Migrations aplicadas são imutáveis (checksum, 2.2.1) | `scripts/ci/classificar-migrations.php`: reprova PR que altera/remove migration existente |
| Linting de SQL no PR (2.2.2) | `scripts/ci/sql-pendente.sh`: `migrate --pretend` no estado anterior + Squawk (relatório) |
| Testes contra o SGBD real, sem SQLite (2.2.2) | PostgreSQL efêmero no agente; `phpunit.xml` sem override de SQLite; `.env.testing.example` |
| Revisão por pares para mudança aditiva (Tabela 2) | Política: mínimo de 1 revisor |
| Aprovação obrigatória do DBA para mudança crítica (Tabela 2, CODEOWNERS) | Política: revisor obrigatório **DBA Modernizado** para `database/migrations/criticas/*` |
| Proteção da própria política (2.3.4) | O mesmo revisor obrigatório vale para `azure-pipelines-*.yml`, `pipelines/*` e `scripts/*` |
| Conta de serviço de migração ≠ conta da aplicação (2.3.1, passo 4) | CD: `lab_migrator` aplica; `lab_app` só tem DML e o pipeline prova que DDL é negado |
| Rastreabilidade em duas camadas (2.3.1) | Git/PR (autor, revisor, data) + tabela `migrations` do Laravel + histórico do ambiente `producao-lab` |
| Backup testado, RTO e RPO (2.4) | CD: backup antes da mudança e ensaio de restauração cronometrado em todo deploy |

> Limitação, como no trabalho: a classificação é por **padrão de código e caminho**, não por análise
> semântica do SQL, e o laboratório não reproduz carga concorrente nem volume de produção.

## Estrutura

```
app/Services/ClienteService.php         acesso da aplicação à tabela clientes (muda a cada fase)
database/migrations/                    migrations de baixo risco (aditivas)
database/migrations/criticas/           migrations críticas: revisor obrigatório do DBA
database/seeders/ClientesFicticiosSeeder.php   massa fictícia, adaptada à fase do esquema
tests/Feature/                          testes de integração (PostgreSQL)
scripts/ci/                             guardrails do PR
scripts/cd/producao-lab.sh              implantação na produção do laboratório
azure-pipelines-pr.yml                  validação de PR (chamada pela política da main)
azure-pipelines-main.yml                CI + CD a cada merge na main
pipelines/ci-steps.yml                  passos de CI compartilhados
docs/                                   roteiro da demonstração e plano de recuperação
```

## Roteiro da demonstração (branches e PRs)

| Ordem | Branch | O que mostra | Resultado esperado |
|---|---|---|---|
| 0 | `main` | Estado inicial: `clientes.email` NOT NULL | — |
| 1 | `demo/cenario-falha-rename` | `renameColumn` direto, fora de `criticas/` | **PR bloqueado** pelo guardrail e pelos testes |
| 2 | `feature/01-expand-email-corporativo` | Expand + Migrate: coluna nova anulável, dual-write, backfill em lotes | Pipeline verde; basta revisão por pares |
| 3 | `feature/02-contract-preparacao` | `email` vira anulável (`->change()`), aplicação passa a usar só `email_corporativo` | Exige aprovação do **DBA Modernizado** |
| 4 | `feature/03-contract-remocao` | `dropColumn('email')` | Exige aprovação do **DBA Modernizado** |

Os PRs 3 e 4 devem ser abertos **um de cada vez, depois do merge do anterior**, como releases
separadas (seção 2.3.4: "a remoção só ocorre após estabilidade confirmada"). Detalhes e evidências a
registrar: [docs/roteiro-demonstracao.md](docs/roteiro-demonstracao.md).

## Rodar localmente (opcional)

Requer PHP 8.2+ com `pdo_pgsql` e um PostgreSQL de testes (nunca o de produção):

```bash
composer install
cp .env.testing.example .env.testing   # ajuste host/usuário/senha do seu PostgreSQL de testes
php artisan migrate --env=testing
php artisan test --env=testing
```

## Custo

Projeto desenhado para **custo zero** numa organização gratuita do Azure DevOps:

- pipelines só no job Microsoft-hosted gratuito (1 paralelo, 1.800 min/mês), com timeout de 15 min;
- nenhum recurso do Azure (App Service, banco gerenciado, storage), nenhuma service connection;
- nenhum artefato nem pacote publicado (Azure Artifacts), nenhuma extensão paga;
- Boards, Repos, políticas de branch e ambientes de pipeline são gratuitos.

Se a cota gratuita de minutos acabar, os pipelines apenas ficam na fila até o mês seguinte; não há
cobrança enquanto a organização não tiver paralelismo comprado nem cobrança configurada.
