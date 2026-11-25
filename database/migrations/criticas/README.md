# Migrations críticas

Toda migration que altera tipo, torna coluna obrigatória/opcional (`->change()`), renomeia ou remove
estrutura (`dropColumn`, `dropTable`, `renameColumn`, `DROP`, `ALTER ... TYPE`) **tem que** ficar nesta pasta.

- O pipeline de PR reprova migrations críticas fora daqui (`scripts/ci/classificar-migrations.php`).
- A política da branch `main` exige aprovação do time **DBA Modernizado** para qualquer arquivo nesta pasta
  (e para os próprios pipelines e scripts de CI, protegendo a política).

Matriz RACI (Tabela 2 do trabalho): o Dev Pragmático é **R** (implementa as fases do Expand-Contract)
e o DBA Modernizado é **A** (aprovação técnica obrigatória).
