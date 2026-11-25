# Roteiro da demonstração

Segue a seção 2.3.4 do trabalho. Cada etapa é um PR para a `main`; as evidências (saída dos comandos,
dos testes e da revisão) ficam registradas no próprio Azure DevOps.

## Etapa 1: cenário de falha (`demo/cenario-falha-rename`)

Migration que renomeia `email` para `email_corporativo` de uma vez, fora de `criticas/`.

- **Guardrail**: reprova o PR ("Migration CRÍTICA (renameColumn/rename) fora de database/migrations/criticas/").
- **SQL pendente**: mostra o `ALTER TABLE ... RENAME COLUMN` que seria executado.
- **Testes**: `ClienteServiceTest` falha com coluna inexistente (`email`), demonstrando que a versão antiga
  da aplicação quebra no instante em que a migration roda.
- **Evidência**: aba de testes e resumo do pipeline do PR. **Não concluir este PR** (abandonar ao final).

## Etapa 2: Expand + Migrate (`feature/01-expand-email-corporativo`)

- Migration aditiva: `email_corporativo` anulável (baixo risco → só revisão por pares).
- Aplicação intermediária grava nas duas colunas e continua lendo `email` (dual-write).
- `php artisan clientes:backfill-email-corporativo` preenche em lotes (`chunkById`) sem sobrescrever.
- `php artisan clientes:verificar-integridade` confere que não há nulos nem divergências.
- Teste `test_compatibilidade_do_esquema` (o mesmo do trabalho) passa durante a coexistência.
- **Após o merge**: o pipeline da main aplica na produção do laboratório (20 mil clientes fictícios, metade
  sem a coluna nova), roda o backfill e a verificação, e registra o ensaio de restauração.

## Etapa 3: preparação do Contract (`feature/02-contract-preparacao`)

- Migration em `criticas/`: `email` passa a anulável (`->change()`), necessário antes de parar o dual-write.
- Aplicação passa a ler e gravar **somente** `email_corporativo`; testes atualizados para o novo contrato.
- **Política**: o PR fica aguardando o revisor obrigatório **DBA Modernizado**.

## Etapa 4: Contract (`feature/03-contract-remocao`)

- Migration em `criticas/`: `dropColumn('email')`, em release posterior, com o plano de recuperação abaixo.
- **Política**: revisor obrigatório **DBA Modernizado**.
- O CD faz backup antes do DROP e prova que a restauração funciona (Nível 3 do plano).

## O que registrar na apresentação

1. Print do PR da etapa 1 bloqueado (guardrail + testes vermelhos).
2. Print do resumo "Classificação das migrations" de cada PR.
3. Print do PR da etapa 3 ou 4 aguardando o revisor obrigatório **DBA Modernizado**.
4. Print do resumo "Produção do laboratório" do pipeline da main (tempos de backup, migração e restauração;
   bloqueio de DDL para a conta da aplicação).
5. Histórico do ambiente `producao-lab` (qual commit está "em produção").
