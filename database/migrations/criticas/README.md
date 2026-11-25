# Migrations críticas

Migrations que podem **perder dados, travar tabelas grandes ou quebrar a versão em produção** ficam aqui.
O caminho `database/migrations/criticas/*` tem **revisor obrigatório: grupo DBA Modernizado** na política
da `main`. Todo o resto (tabela nova, coluna anulável, índice em tabela pequena) passa só com revisão
por pares e com o pipeline verde.

O guardrail do PR (`scripts/ci/classificar-migrations.php`) reprova operação crítica fora desta pasta:

- `->change()`, `renameColumn`, `dropColumn`, `Schema::drop*`, `drop*` de índice ou chave;
- SQL bruto (`DB::statement`, `DB::unprepared`);
- `truncate()` / `delete()` em massa.

O Laravel carrega esta pasta junto com `database/migrations` (`AppServiceProvider::boot`) e executa tudo
em ordem de nome (data e hora no começo do arquivo).
