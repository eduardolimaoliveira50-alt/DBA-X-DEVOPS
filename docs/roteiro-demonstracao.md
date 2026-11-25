# Roteiro da demonstração: da task na sprint até a produção

Visão defendida: **DBA Pragmático + Modernizado**. O banco é praticamente todo gerenciado por migrations;
o DBA criou o banco uma vez (`scripts/dba/criar-banco.sh`) e só atua quando o pipeline prova que precisa.

- Boards: **Sprint Almoxarifado** (Boards > Sprints), uma história por cenário, com a task de desenvolvimento.
- Repos: `almoxarifado`. Cada cenário tem a branch do desenvolvedor (`feature/...`) e, quando o DBA
  precisa intervir, a correção dele já preparada (`dba/...`, um commit em cima da feature).
- Produção: https://almoxarifado-iwpm.onrender.com (contas de demonstração na tela de login).

> **Antes de apresentar:** abra a URL uns minutos antes (no plano gratuito o serviço "dorme" depois de
> 15 min parado e leva ~1 min para acordar). Cada pipeline de PR leva ~5 min; o da main, ~8 min.

## 0. Configuração da publicação (PR inicial)

PR `chore/publicacao-render -> main`, vinculado à task "Configurar serviço do Render...". Precisa entrar
**antes dos cenários**. Mexe em `scripts/ci` e `pipelines/`: o revisor obrigatório **DBA Modernizado** é
acionado (a própria política de CI também é protegida). Aprovar, concluir, e o `almox-main` publica.

## Cenário 1: mudança comum, o DBA nem é chamado

História **"Localização do produto no almoxarifado"**.

1. Boards: mover a task para *Doing*.
2. Repos > Pull requests > New: `feature/101-localizacao-produto -> main`; vincular a task; criar.
3. Pipeline do PR (`almox-pr`):
   - Guardrail: `BAIXO RISCO (aditiva)`;
   - testes verdes;
   - ensaio em staging verde: coluna anulável não trava nada.
4. Só a revisão por pares é exigida: aprovar e concluir.
5. `almox-main` roda CI, backup e restauração cronometrada, e publica.
6. Na URL: Produtos > editar mostra o campo novo. Banco de dados mostra a migration no último lote.

**Mensagem:** a maior parte das mudanças segue sem gatekeeper. Velocidade com segurança, porque o
pipeline verificou com dados de produção.

## Cenário 2: UNIQUE com dados duplicados (o ensaio pega)

História **"Código do produto não pode repetir"**.

1. PR `feature/102-codigo-unico -> main`, vinculado à task.
2. Pipeline vermelho:
   - guardrail amarelo (`MODERADA`);
   - **testes verdes**, porque o banco vazio aceita;
   - **ensaio em staging falha**:
     > Ensaio em staging: a migration funciona no banco vazio dos testes, mas FALHA com os dados de produção
     > (SQLSTATE 23505): dados DUPLICADOS na produção impedem a restrição de unicidade...
     > Intervenção do DBA necessária.
   - Aba *Extensions* do build: o resumo mostra o SQL pendente e o erro
     (`Key (codigo)=(MAT-…) is duplicated`).
3. Boards: criar a task **"DBA: sanear códigos repetidos"** na história e atribuir ao DBA.
4. O DBA decide com o negócio: o cadastro mais antigo de cada código fica; movimentações e saldo dos
   repetidos são consolidados nele; os removidos ficam guardados (`produtos_consolidados`).
5. Correção:
   - abrir PR `dba/102-codigo-unico -> feature/102-codigo-unico` e concluir (sem políticas: é a branch da
     feature);
   - ou, no terminal: `git push origin dba/102-codigo-unico:feature/102-codigo-unico`.
6. O PR principal roda de novo sozinho (**rerun**). Agora fica verde:
   - a consolidação roda antes do UNIQUE;
   - a integridade é conferida (saldo = soma das movimentações).
7. Como agora há migration em `criticas/`, o PR pede o revisor obrigatório **DBA Modernizado**:
   aprovar como DBA e concluir.
8. Produção: Banco de dados mostra `produtos_codigo_unique` e a migration de consolidação. Produtos >
   buscar `MAT-00132` (um dos repetidos) mostra um cadastro só.

## Cenário 3: NOT NULL em coluna com dados antigos nulos

História **"CNPJ obrigatório no cadastro de fornecedores"**.

1. PR `feature/103-cnpj-obrigatorio -> main`. O desenvolvedor já colocou a migration (`->change()`) em
   `criticas/`, então o **DBA Modernizado** já aparece como revisor obrigatório.
2. Pipeline vermelho: **ensaio falha com SQLSTATE 23502**. Há linhas antigas com valor nulo, e o log diz:
   > Não dá para inventar o dado: o DBA define a estratégia...
3. Na produção (Fornecedores), o aviso mostra **66 fornecedores sem CNPJ** do cadastro antigo.
4. Intervenção do DBA (`dba/103-cnpj-obrigatorio`): em vez de NOT NULL,
   `CHECK (cnpj IS NOT NULL OR deleted_at IS NOT NULL) NOT VALID`.
   - Vale **já** para toda linha nova ou alterada.
   - Não varre a tabela.
   - Os antigos ficam para o saneamento: o item "Sanear CNPJ..." fica no backlog, fora da sprint.
5. Rerun verde. O DBA aprova e conclui.
6. Produção: Banco de dados > Restrições mostra `fornecedores_cnpj_obrigatorio` com o selo **NOT VALID**.

## Cenário 4: índice que travaria a tabela grande

História **"Movimentações lentas ao filtrar produto por período"**.

1. PR `feature/104-indice-movimentacoes -> main`. A migration está fora de `criticas/` porque é "só um
   índice".
2. Pipeline vermelho:
   - os testes passam;
   - a migration até aplica;
   - a **Análise de bloqueio** reprova:
     > Risco de indisponibilidade: CREATE INDEX sem CONCURRENTLY em movimentacoes (~300.000 linhas)
     > bloqueia INSERT/UPDATE/DELETE...
3. Intervenção do DBA (`dba/104-indice-movimentacoes`):
   - migration nova em `criticas/` com `CREATE INDEX CONCURRENTLY` e `public $withinTransaction = false`;
   - a migration do desenvolvedor sai (ainda não estava na main, então pode).
4. Rerun verde. O DBA aprova e conclui.
5. Produção: Banco de dados > Índices mostra `movimentacoes_produto_data_index`.

## O que mostrar em cada etapa

| Onde | O quê |
|---|---|
| Boards > Sprints | a história, a task do dev e a task do DBA criada só quando foi necessária |
| PR > Overview | políticas: build, 1 revisor, comentários, work item, DBA (só quando há `criticas/`) |
| Build > Summary / Extensions | "Classificação das migrations" e "Ensaio em staging" (massa, SQL, análise de bloqueio, tempos) |
| Build > Tests | testes automatizados |
| Pipelines > Environments > almoxarifado-producao | histórico de implantações (qual commit está em produção) |
| Aplicação > Banco de dados | migrations aplicadas, índices, restrições (NOT VALID), colunas |

## Ligação com o trabalho

- **DBA Modernizado:** o DBA escreveu a régua uma vez:
  - o provisionamento (`criar-banco.sh`);
  - o guardrail;
  - a análise de bloqueio;
  - o ensaio com dados de produção.

  Ele deixou de ser bloqueador e virou arquiteto do processo.
- **Desenvolvedor Pragmático:** automação onde é seguro (cenário 1) e validação humana onde é crítico
  (cenários 2 a 4), decidida por evidência e não por desconfiança.
- **Por que os testes não bastam:** nos cenários 2 e 3, todos os testes passaram. Só o ensaio com o
  formato da produção mostrou o problema, e só o DBA podia decidir o que fazer com os dados.
- **Recuperação:** a cada merge, backup antes da mudança e restauração cronometrada (RTO medido). Na
  produção, se uma migration falhar na subida, o Render mantém a versão anterior no ar.

## Refazer a demonstração

Os PRs concluídos mudam a main. Para ensaiar de novo, peça para recriar o repositório a partir do commit
inicial e as branches dos cenários. O banco do Render precisa ser recriado à parte.
