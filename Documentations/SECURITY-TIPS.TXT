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

- Avoid keep your Asterisk server visible to the Internet;
- Never use an IP phone with valid Internet address, you'll probably have default passwords and other security flaws;
- If you really need to keep an Asterisk server visible at Internet, be sure to:
  * Use strong passwords (at least 8 characters, lower and upper case letters, numbers and special characters);
  * Change your user passwords periodically (every 2 to 3 months at most);
  * Prefer to enable only TLS port, and enforce use of SRTP;
  * Try to block call's to unusual destinations.
- At sip.conf under the general section, use "alwaysauthreject = yes". This setting make's Asterisk reply with authentication error insted of not found even if peer didn't exist;
- Think about use Fail2Ban to block address that make many requests with invalid password.
