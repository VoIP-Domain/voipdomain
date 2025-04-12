/**   ___ ___       ___ _______     ______                        __
 *   |   Y   .-----|   |   _   |   |   _  \ .-----.--------.---.-|__.-----.
 *   |.  |   |  _  |.  |.  1   |   |.  |   \|  _  |        |  _  |  |     |
 *   |.  |   |_____|.  |.  ____|   |.  |    |_____|__|__|__|___._|__|__|__|
 *   |:  1   |     |:  |:  |       |:  1    /
 *    \:.. ./      |::.|::.|       |::.. . /
 *     `---'       `---`---'       `------'
 *
 * Copyright (C) 2016-2018 Ernani José Camargo Azevedo
 *
 * Este programa é software livre: você pode redistribuí-lo e/ou modificar sob os
 * termos da Licença Pública Geral GNU, conforme publicada pela Free Software
 * Foundation, versão 3 da Licença, ou (a seu critério) qualquer versão
 * posterior.
 *
 * Este programa é distribuído na esperança de que seja útil, mas SEM QUALQUER
 * GARANTIA; sem mesmo a garantia implícita de COMERCIABILIDADE OU ADEQUAÇÃO A UM
 * DETERMINADO FIM. Veja a licença GNU General Public License mais detalhes.
 *
 * Você deve ter recebido uma cópia da Licença Pública Geral GNU junto com este
 * programa. Se não, veja <https://www.gnu.org/licenses/>.
 */

  Dicas de Segurança do Asterisk
--==============================--

- Evite deixar o seu servidor Asterisk visível para a Internet;
- Nunca utilize um telefone IP com endereço de Internet válido, você provavelmente terá senhas padrões e outros falhas de segurança;
- Se você realmente necessita manter o servidor Asterisk visível para a Internet, tenha certeza de:
  * Utilizar senhas fortes (no mínimo 8 caracteres, letras minúsculas e maiúsculas, números e caracteres especiais);
  * Troque as suas senhas periodicamente (no mínimo a cada 2 a 3 meses);
  * Habilite preferencialmente só a porta TLS, e forçe o uso se SRTP;
  * Tente bloquear ligações para destinos pouco utilizados.
- No arquivo de configuração sip.conf na sessão "general", utilize "alwaysauthreject = yes". Esta configuração faz com que o Asterisk responda com erro de autenticação ao invés de não encontrado para usuários inexistentes;
- Procure utilizar um Fail2Ban para bloquear endereços que realizem muitas requisições com senha errada.
