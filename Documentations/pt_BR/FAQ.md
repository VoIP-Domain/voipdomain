  FAQ do Desenvolvedor VoIP Domain
--==================================--

> **Referência completa:** [MANUAL-DO-DESENVOLVEDOR.md](MANUAL-DO-DESENVOLVEDOR.md) · [Índice da documentação](README.md)

P- O meu número não aparece na caixa de seleção do hunt group!
R- A lista de tipos de extensão é filtrada para incluir apenas tipos presentes na configuração global `$_in["hunts"]`. Veja `modules/extensions-phones/config.php` para um exemplo dos tipos a incluir. Este filtro existe para evitar tipos recursivos e que não são endpoints.

P- Existe forma de executar código ao instalar, actualizar ou remover um plugin?
R- Sim. Registe o hook `plugin_NOMEDOPLUGIN_{install,upgrade,remove}`. Quando disparado, recebe um array com informação sobre o sistema.

P) Por que a base MySQL do VoIP Domain usa apenas o engine InnoDB?
R) Porque requer suporte a transacções, entre outros requisitos menores.

P) Por que o VoIP Domain usa `START TRANSACTION` em vez de `mysqli::begin_transaction`?
R) Porque `mysqli::begin_transaction` requer PHP ≥ 5.5.0. O foco principal são servidores RHEL; o RHEL 7 envia PHP 5.4.16 por defeito.

P) O VoIP Domain planeja suportar PostgreSQL?
R) Não no momento. O foco é MySQL/MariaDB; PostgreSQL só será considerado se a comunidade fizer a pesquisa e desenvolvimento necessários.

P) Os nomes de eventos Gearman seguem um padrão?
R) Sim. Devem usar `NOMEDOMODULO_ACAO`. Sub-acções adicionais são concatenadas com underscores. Ver manual §8.3 e §13.
