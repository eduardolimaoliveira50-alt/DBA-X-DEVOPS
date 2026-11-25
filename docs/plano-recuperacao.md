# Plano de recuperação (seção 2.4 do trabalho)

Reverter o código não devolve dados apagados. Por isso cada nível tem meta de RTO/RPO própria.

| Nível | Situação | Ação | RTO | RPO |
|---|---|---|---|---|
| 1 | Erro de aplicação sem dano aos dados | Reverter o merge (novo PR de revert) e reimplantar; o Expand-Contract mantém o esquema compatível | < 5 min | zero |
| 2 | Erro lógico localizado (UPDATE errado) | Forward fix com dados de um clone do último backup, preservando transações legítimas posteriores | 15 a 45 min | ≈ zero |
| 3 | Tabela/coluna removida ou corrupção severa | Restauração do backup (no laboratório: `pg_restore` do dump feito antes da mudança; em produção real: PITR com WAL) | 30 min a 2 h | segundos a minutos |

## Backup testado

Todo deploy do pipeline da main:

1. faz `pg_dump` imediatamente antes de aplicar as migrations;
2. aplica as migrations com a conta de serviço;
3. restaura o dump num banco separado, cronometra e confere a quantidade de registros.

O tempo medido aparece no resumo da execução ("Produção do laboratório") e serve como evidência de RTO.

> No laboratório não há WAL arquivado contínuo (o banco é efêmero), então o PITR é descrito, não executado.
> Em produção real, a mesma etapa seria feita com pgBackRest/WAL-G num ambiente isolado, semanalmente.
