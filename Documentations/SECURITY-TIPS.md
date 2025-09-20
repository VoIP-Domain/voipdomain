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
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

  Asterisk Security Tips
--======================--

- Avoid keeping your Asterisk server exposed to the Internet.
- Never use an IP phone with a public Internet address; it will likely have default passwords and other security flaws.
- If you really need to expose an Asterisk server to the Internet, be sure to:
  * Use strong passwords (at least 8 characters, lower and upper case letters, numbers and special characters);
  * Change your user passwords periodically (every 2 to 3 months at most);
  * Prefer to enable only the TLS port and enforce the use of SRTP;
  * Try to block calls to unusual destinations.
- In `sip.conf` under the `[general]` section, set `alwaysauthreject = yes`. This setting makes Asterisk reply with an authentication error instead of “not found”, even when the peer does not exist.
- Consider using Fail2Ban to block addresses that make repeated requests with an invalid password.
