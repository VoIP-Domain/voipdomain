<?php
/**   ___ ___       ___ _______     ______                        __
 *   |   Y   .-----|   |   _   |   |   _  \ .-----.--------.---.-|__.-----.
 *   |.  |   |  _  |.  |.  1   |   |.  |   \|  _  |        |  _  |  |     |
 *   |.  |   |_____|.  |.  ____|   |.  |    |_____|__|__|__|___._|__|__|__|
 *   |:  1   |     |:  |:  |       |:  1    /
 *    \:.. ./      |::.|::.|       |::.. . /
 *     `---'       `---`---'       `------'
 *
 * Copyright (C) 2016-2025 Ernani José Camargo Azevedo
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

/**
 * VoIP Domain notifications module language file.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Notifications
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add basic language structure (english)
 */
i18n_add ( "Search notifications");
i18n_add ( "Notifications");
i18n_add ( "notifications search");
i18n_add ( "Event");
i18n_add ( "Method");
i18n_add ( "URL");
i18n_add ( "Data type");
i18n_add ( "Filters");
i18n_add ( "Mapping");
i18n_add ( "Value");
i18n_add ( "Variable");
i18n_add ( "Header");
i18n_add ( "Headers");
i18n_add ( "Header variable");
i18n_add ( "Relax SSL?");
i18n_add ( "Validity");
i18n_add ( "Remove notifications");
i18n_add ( "Are sure you want to remove the notification %s (event \"%s\")?");
i18n_add ( "Notification remove");
i18n_add ( "Notification removed sucessfully!");
i18n_add ( "Error removing notification!");
i18n_add ( "notifications addition");
i18n_add ( "Notification description");
i18n_add ( "Notification event");
i18n_add ( "Notification method");
i18n_add ( "Notification URL");
i18n_add ( "Notification data type");
i18n_add ( "Notification validity");
i18n_add ( "Notification addition");
i18n_add ( "Error adding notification!");
i18n_add ( "Notification added sucessfully!");
i18n_add ( "notifications view");
i18n_add ( "Notification view");
i18n_add ( "Error requesting notification data!");
i18n_add ( "notifications edition");
i18n_add ( "Notification edition");
i18n_add ( "Error changing notification!");
i18n_add ( "Notification changed sucessfully!");
i18n_add ( "View notification information");
i18n_add ( "Add notifications");
i18n_add ( "N/A");
i18n_add ( "The data type is required.");
i18n_add ( "The description is required.");
i18n_add ( "The event is required.");
i18n_add ( "The method is required.");
i18n_add ( "The provided data type is invalid.");
i18n_add ( "The provided event and URL already exist.");
i18n_add ( "The provided event is invalid.");
i18n_add ( "The provided method is invalid.");
i18n_add ( "The URL is required.");
i18n_add ( "Edit notifications");
i18n_add ( "New call");
i18n_add ( "Event fired when a new call happen at the system.");
i18n_add ( "Select field");
i18n_add ( "Remove header");
i18n_add ( "Add header");
i18n_add ( "Show calendar");
i18n_add ( "Filter search with this string. If not provided, return all notification events.");
i18n_add ( "An array containing the system notification event.");
i18n_add ( "The internal unique identification number of the notification event.");
i18n_add ( "The event name of the notification event.");
i18n_add ( "New Call");
i18n_add ( "The original English event name of the notification event.");
i18n_add ( "The description of the notification event.");
i18n_add ( "The original English description of the notification event.");
i18n_add ( "Search notification events");
i18n_add ( "Search for system notification events.");
i18n_add ( "An object containing information about the system notification event.");
i18n_add ( "The unique identifier of the notification event.");
i18n_add ( "The translated name of the notification event.");
i18n_add ( "The original English name of the notification event.");
i18n_add ( "The translated description of the notification event.");
i18n_add ( "An array of objects describing notification event fields.");
i18n_add ( "Name of the variable.");
i18n_add ( "The translated name of the variable.");
i18n_add ( "Call Type");
i18n_add ( "The original english name of the variable.");
i18n_add ( "The API path to access variable information.");
i18n_add ( "An array with variable as index and variable description as value. This element is available only if `Type` is *enum*.");
i18n_add ( "Invalid notification event ID.");
i18n_add ( "View notification events structure information");
i18n_add ( "View notifications");
i18n_add ( "Get a system notification information.");
i18n_add ( "The notification internal system unique identifier.");
i18n_add ( "Filter search with this string. If not provided, return all notifications.");
i18n_add ( "An array containing the system notifications.");
i18n_add ( "The translated event of system notification.");
i18n_add ( "The original English event of system notification.");
i18n_add ( "The UNIX timestamp of the expiration of system notification. If doesn't have expiration, will be *0*.");
i18n_add ( "The original English event of system notification. If doesn't have expiration, will be empty.");
i18n_add ( "05/11/2020 17:00:00");
i18n_add ( "Search for system notifications.");
i18n_add ( "An object containing information about the system notification.");
i18n_add ( "The description of the notification.");
i18n_add ( "My event notification");
i18n_add ( "The event this notification watch for.");
i18n_add ( "The name and description in format `name (description)` of the event this notification watch for.");
i18n_add ( "New Call (Event fired when a new call happen at the system.)");
i18n_add ( "An object with the structure of event variables filter.");
i18n_add ( "The HTTP method the system must use to notify the event.");
i18n_add ( "The URL to be notified of event.");
i18n_add ( "The format the data must be sent to notification endpoint.");
i18n_add ( "An indexed array with the variables mapping for the event. The index will be the variable name and the value the variable used to notify the endpoint.");
i18n_add ( "An indexed array with the extra HTTP headers that will be used to notify the endpoint. The index will be the header variable and the value will be the header value.");
i18n_add ( "If the system must relax SSL validation on HTTPS connection.");
i18n_add ( "The expiration date of notification in YYYY-MM-DD format. If doesn't have expiration, will be *empty*.");
i18n_add ( "Invalid notification ID.");
i18n_add ( "The description of the system notification.");
i18n_add ( "An array containing all notification variables mapping, with array index as variable name and value as name to be used.");
i18n_add ( "An array containing request special headers to be used.");
i18n_add ( "The reference number to header. This is used to report any header name/value error.");
i18n_add ( "The header name. This should not include reserved values and will be appended to 'X-'.");
i18n_add ( "The value to be sent with the special header.");
i18n_add ( "If the system must relax SSL certificate validation if using HTTPS.");
i18n_add ( "The date of validity to this notification in YYYY-MM-DD format.");
i18n_add ( "New system notification added sucessfully.");
i18n_add ( "The provided extra HTTP header name are invalid.");
i18n_add ( "The provided extra HTTP header value cannot be empty.");
i18n_add ( "Add a new system notification.");
i18n_add ( "The system notification was sucessfully updated.");
i18n_add ( "Change a system notification information.");
i18n_add ( "The system notification was removed.");
i18n_add ( "Remove a notification from system.");
i18n_add ( "Variable name");
i18n_add ( "Error requesting notification structure data!");
i18n_add ( "Notification cloning");

/**
 * Add Brazilian Portuguese support
 */
i18n_add ( "Search notifications", "Pesquisar notificações", "pt_BR");
i18n_add ( "Notifications", "Notificações", "pt_BR");
i18n_add ( "notifications search", "pesquisa de notificações", "pt_BR");
i18n_add ( "Event", "Evento", "pt_BR");
i18n_add ( "Method", "Método", "pt_BR");
i18n_add ( "URL", "URL", "pt_BR");
i18n_add ( "Data type", "Tipo de dados", "pt_BR");
i18n_add ( "Filters", "Filtros", "pt_BR");
i18n_add ( "Header", "Cabeçalho", "pt_BR");
i18n_add ( "Headers", "Cabeçalhos", "pt_BR");
i18n_add ( "Header variable", "Variável do cabeçalho", "pt_BR");
i18n_add ( "Mapping", "Mapeamento", "pt_BR");
i18n_add ( "Value", "Valor", "pt_BR");
i18n_add ( "Variable", "Variável", "pt_BR");
i18n_add ( "Relax SSL?", "Relaxar SSL?", "pt_BR");
i18n_add ( "Validity", "Validade", "pt_BR");
i18n_add ( "Remove notifications", "Remover notificações", "pt_BR");
i18n_add ( "Are sure you want to remove the notification %s (event \"%s\")?", "Você tem certeza que deseja remover a notificação %s (evento \"%s\")?", "pt_BR");
i18n_add ( "Notification remove", "Remoção de notificação", "pt_BR");
i18n_add ( "Notification removed sucessfully!", "Notificação removida com sucesso!", "pt_BR");
i18n_add ( "Error removing notification!", "Erro ao remover notificação!", "pt_BR");
i18n_add ( "notifications addition", "adição de notificação", "pt_BR");
i18n_add ( "Notification description", "Descrição da notificação", "pt_BR");
i18n_add ( "Notification event", "Evento da notificação", "pt_BR");
i18n_add ( "Notification method", "Método da notificação", "pt_BR");
i18n_add ( "Notification URL", "URL da notificação", "pt_BR");
i18n_add ( "Notification data type", "Tipo de dados da notificação", "pt_BR");
i18n_add ( "Notification validity", "Validade da notificação", "pt_BR");
i18n_add ( "Notification addition", "Adição de notificação", "pt_BR");
i18n_add ( "Error adding notification!", "Erro ao adicionar notificação!", "pt_BR");
i18n_add ( "Notification added sucessfully!", "Notificação adicionado com sucesso!", "pt_BR");
i18n_add ( "notifications view", "visualização de notificações", "pt_BR");
i18n_add ( "Notification view", "Visualização de notificação", "pt_BR");
i18n_add ( "Error requesting notification data!", "Erro ao requisitar dados da notificação!", "pt_BR");
i18n_add ( "notifications edition", "edição de notificações", "pt_BR");
i18n_add ( "Notification edition", "Edição de notificação", "pt_BR");
i18n_add ( "Error changing notification!", "Erro ao alterar notificação!", "pt_BR");
i18n_add ( "Notification changed sucessfully!", "Notificação alterada com sucesso!", "pt_BR");
i18n_add ( "View notification information", "Visualizar informações de notificações", "pt_BR");
i18n_add ( "Add notifications", "Adicionar notificações", "pt_BR");
i18n_add ( "N/A", "N/D", "pt_BR");
i18n_add ( "The description is required.", "A descrição é obrigatória.", "pt_BR");
i18n_add ( "The event is required.", "O evento é obrigatório.", "pt_BR");
i18n_add ( "The data type is required.", "O tipo de dados é obrigatório.", "pt_BR");
i18n_add ( "The method is required.", "O método é obrigatório.", "pt_BR");
i18n_add ( "The provided data type is invalid.", "O tipo de dado informado é inválido.", "pt_BR");
i18n_add ( "The provided event and URL already exist.", "O evento e URL informadas já existem.", "pt_BR");
i18n_add ( "The provided event is invalid.", "O evento informado é inválido.", "pt_BR");
i18n_add ( "The provided method is invalid.", "O método informado é inválido.", "pt_BR");
i18n_add ( "The URL is required.", "A URL é obrigatória.", "pt_BR");
i18n_add ( "Edit notifications", "Editar notificações", "pt_BR");
i18n_add ( "New call", "Nova ligação", "pt_BR");
i18n_add ( "Event fired when a new call happen at the system.", "Evento disparado quando uma nova ligação entra no sistema.", "pt_BR");
i18n_add ( "Select field", "Selecione o campo", "pt_BR");
i18n_add ( "Remove header", "Remover cabeçalho", "pt_BR");
i18n_add ( "Add header", "Adicionar cabeçalho", "pt_BR");
i18n_add ( "Show calendar", "Exibir calendário", "pt_BR");
i18n_add ( "Filter search with this string. If not provided, return all notification events.", "Filtra pequisa por esta string. Se não fornecida, retorna todos eventos de notificação.", "pt_BR");
i18n_add ( "An array containing the system notification event.", "Um array contendo o evento de notificação do sistema.", "pt_BR");
i18n_add ( "The internal unique identification number of the notification event.", "O número identificador interno único do evento de notificação.", "pt_BR");
i18n_add ( "The event name of the notification event.", "O nome do evento de notificação.", "pt_BR");
i18n_add ( "New Call", "Nova Chamada", "pt_BR");
i18n_add ( "The original English event name of the notification event.", "O nome original do evento em Inglês do evento de notificação.", "pt_BR");
i18n_add ( "The description of the notification event.", "A descrição do evento de notificação.", "pt_BR");
i18n_add ( "The original English description of the notification event.", "A descrição original em Inglês do evento de notificação.", "pt_BR");
i18n_add ( "Search notification events", "Pesquisa eventos de notificação", "pt_BR");
i18n_add ( "Search for system notification events.", "Pesquisa por eventos de notificação do sistema.", "pt_BR");
i18n_add ( "An object containing information about the system notification event.", "Um objeto contendo informações sobre o evento de notificação do sistema.", "pt_BR");
i18n_add ( "The unique identifier of the notification event.", "O identificador único do evento de notificação.", "pt_BR");
i18n_add ( "The translated name of the notification event.", "O nome traduzido do evento de notificação.", "pt_BR");
i18n_add ( "The original English name of the notification event.", "O nome original em Inglês do evento de notificação.", "pt_BR");
i18n_add ( "The translated description of the notification event.", "A descrição traduzida do evento de notificação.", "pt_BR");
i18n_add ( "An array of objects describing notification event fields.", "Um array de objetos descrevendo os campos do evento de notificação.", "pt_BR");
i18n_add ( "Name of the variable.", "Nome da variável.", "pt_BR");
i18n_add ( "The translated name of the variable.", "O nome traduzido da variável.", "pt_BR");
i18n_add ( "Call Type", "Tipo de Chamada", "pt_BR");
i18n_add ( "The original english name of the variable.", "O nome original em Inglês da variável.", "pt_BR");
i18n_add ( "The API path to access variable information.", "O caminho da API para acessar informações da variável.", "pt_BR");
i18n_add ( "An array with variable as index and variable description as value. This element is available only if `Type` is *enum*.", "Um array sendo a variável como indexador e descrição da variável como valor. Este elemento é disponível apenas se `Tipo` for *enum*.", "pt_BR");
i18n_add ( "Invalid notification event ID.", "ID de notificação de evento inválido.", "pt_BR");
i18n_add ( "View notification events structure information", "Visualizar informações de estrutura de notificações de eventos", "pt_BR");
i18n_add ( "View notifications", "Visualizar notificações", "pt_BR");
i18n_add ( "Get a system notification information.", "Requisitar informações de notificação do sistema.", "pt_BR");
i18n_add ( "The notification internal system unique identifier.", "O identificador interno único de notificação do sistema.", "pt_BR");
i18n_add ( "Filter search with this string. If not provided, return all notifications.", "Filtrar a pesquisa por esta string. Se não informada, retorna todas notificações.", "pt_BR");
i18n_add ( "An array containing the system notifications.", "Um array contendo as notificações do sistema.", "pt_BR");
i18n_add ( "The translated event of system notification.", "O evento traduzido da notificação do sistema.", "pt_BR");
i18n_add ( "The original English event of system notification.", "O evento original em Inglês da notificação do sistema.", "pt_BR");
i18n_add ( "The UNIX timestamp of the expiration of system notification. If doesn't have expiration, will be *0*.", "O timestamp UNIX da expiração da notificação do sistema. Se não houver expiração, será *0*.", "pt_BR");
i18n_add ( "The original English event of system notification. If doesn't have expiration, will be empty.", "O evento original em Inglês da notificação do sistema. Se não houver expiração, será vazio.", "pt_BR");
i18n_add ( "05/11/2020 17:00:00", "11/05/2020 17:00:00", "pt_BR");
i18n_add ( "Search for system notifications.", "Pesquisa por notificações do sistema.", "pt_BR");
i18n_add ( "An object containing information about the system notification.", "Um objeto contendo informações sobre a notificação do sistema.", "pt_BR");
i18n_add ( "The description of the notification.", "A descrição da notificação.", "pt_BR");
i18n_add ( "My event notification", "Meu evento de notificação", "pt_BR");
i18n_add ( "The event this notification watch for.", "O evento que esta notificação monitora.", "pt_BR");
i18n_add ( "The name and description in format `name (description)` of the event this notification watch for.", "O nome e descrição do evento no formato `nome (descrição)` que esta notificação monitora.", "pt_BR");
i18n_add ( "New Call (Event fired when a new call happen at the system.)", "Nova Chamada (Evento disparado quando uma nova chamada ocorre no sistema.)", "pt_BR");
i18n_add ( "An object with the structure of event variables filter.", "Um objeto contendo a estrutura de filtros das variáveis do evento.", "pt_BR");
i18n_add ( "The HTTP method the system must use to notify the event.", "O método HTTP que o sistema deve utilizar para notificar o evento.", "pt_BR");
i18n_add ( "The URL to be notified of event.", "A URL para notificar o evento.", "pt_BR");
i18n_add ( "The format the data must be sent to notification endpoint.", "O formato de dados utilizado para enviar a notificação para o endpoint.", "pt_BR");
i18n_add ( "An indexed array with the variables mapping for the event. The index will be the variable name and the value the variable used to notify the endpoint.", "Um array indexado com o mapeamento de variáveis para o evento. O indexador será o nome da variável e o valor será a variável a ser utilizada para notificar o endpoint.", "pt_BR");
i18n_add ( "An indexed array with the extra HTTP headers that will be used to notify the endpoint. The index will be the header variable and the value will be the header value.", "Um array indexado com os cabeçalhos HTTP adicionais que serão utilizados para notificar o endpoint. O indexador será a variável de cabeçalho e o valor será o conteúdo do cabeçalho.", "pt_BR");
i18n_add ( "If the system must relax SSL validation on HTTPS connection.", "Se o sistema deve desconsiderar a validação SSL em conexões HTTPS.", "pt_BR");
i18n_add ( "The expiration date of notification in YYYY-MM-DD format. If doesn't have expiration, will be *empty*.", "A data de expiração da notificação no formato AAAA-MM-DD. Se não houver expiração, será *vazio*.", "pt_BR");
i18n_add ( "Invalid notification ID.", "ID de notificação inválido.", "pt_BR");
i18n_add ( "The description of the system notification.", "A descrição da notificação do sistema.", "pt_BR");
i18n_add ( "An array containing all notification variables mapping, with array index as variable name and value as name to be used.", "Um array contendo todas variáveis de notificação mapeadas, com o indexador do array sendo o nome da variável e o valor sendo o nome a ser utilizado.", "pt_BR");
i18n_add ( "An array containing request special headers to be used.", "Um array contendo os cabeçalhos especiais a serem utilizados na requisição.", "pt_BR");
i18n_add ( "The reference number to header. This is used to report any header name/value error.", "O número de referência para o cabeçalho. Isto é utilizado para reportar quaisquer erro de nome/valor dos cabeçalhos.", "pt_BR");
i18n_add ( "The header name. This should not include reserved values and will be appended to 'X-'.", "O nome do cabeçalho. Este não deve incluir valores reservados e será apendado com 'X-'.", "pt_BR");
i18n_add ( "The value to be sent with the special header.", "O valor a ser enviado com o cabeçalho especial.", "pt_BR");
i18n_add ( "If the system must relax SSL certificate validation if using HTTPS.", "Se o sistema deve desconsiderar a validação do certificado SSL quando utilizado HTTPS.", "pt_BR");
i18n_add ( "The date of validity to this notification in YYYY-MM-DD format.", "A data de validade desta notificação no formato AAAA-MM-DD.", "pt_BR");
i18n_add ( "New system notification added sucessfully.", "Nova notificação do sistema adicionada com sucesso.", "pt_BR");
i18n_add ( "The provided extra HTTP header name are invalid.", "O nome do cabeçalho HTTP adicional informado é inválido.", "pt_BR");
i18n_add ( "The provided extra HTTP header value cannot be empty.", "O nome do cabeçalho HTTP adicional não pode ser vazio.", "pt_BR");
i18n_add ( "Add a new system notification.", "Adicionar uma nova notificação do sistema.", "pt_BR");
i18n_add ( "The system notification was sucessfully updated.", "A notificação do sistema foi atualizada com sucesso.", "pt_BR");
i18n_add ( "Change a system notification information.", "Altera informações da notificação do sistema.", "pt_BR");
i18n_add ( "The system notification was removed.", "A notificação do sistema foi removida.", "pt_BR");
i18n_add ( "Remove a notification from system.", "Remover a notificação do sistema.", "pt_BR");
i18n_add ( "Variable name", "Nome da variável", "pt_BR");
i18n_add ( "Error requesting notification structure data!", "Erro ao requisitar dados da estrutura da notificiação!", "pt_BR");
i18n_add ( "Notification cloning", "Clonagem de notificação", "pt_BR");
?>
