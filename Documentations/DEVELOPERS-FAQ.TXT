  Database
--========--

Q) Why VoIP Domain MySQL database run only with InnoDB engine?
A) Because of the transaction support, and some others minor requirements.

Q) Why VoIP Domain uses "START TRANSACTION" query instead of mysqli::begin_transaction?
A) Because mysqli::begin_transaction requires PHP >= 5.5.0, and our main focus are on RHEL based servers, and we would like to support RHEL 7 release which has PHP 5.4.16 as default PHP version.

Q) Does VoIP Domain has plan to support PostgreSQL?
A) No. We're focused on MySQL/MariaDB, and PostgreSQL will be available only if the community do some research and a lot of work to support multiple databases.

Q) Does Gearman events name have a standard?
A) Yes. It must use MODULENAME_ACTION. If any other subaction or specification exists, must be added after with underscore separation.

  Framework
--=========--

Q) Which hook's an API call uses?
A) API call's need to implement the usage of the following sub hooks (in this example, the module name is "example"):
   - search:
     example_search_start: Will be executed at hook start. All received parameters will be replaced by buffer output.
     example_search_validate: Will be executed at input data validation. Buffer will contain an array with variable name as ID and content as error message.
     example_search_sanitize: Will be executed after data validation. All received parameters will be replaced by buffer output.
     example_search_pre: Will be executed before database executions. All received parameters will be replaced by buffer output.
     example_search_post: Will be executed after database executions. All output data will be replaced by buffer output.
     example_search_finish: Will be executed before hook execution finishes.
   - view:
     example_view_start: Will be executed at hook start. All received parameters will be replaced by buffer output.
     example_view_validate: Will be executed at input data validation. Buffer will contain an array with variable name as ID and content as error message.
     example_view_sanitize: Will be executed after data validation. All received parameters will be replaced by buffer output.
     example_view_pre: Will be executed before database executions. All received parameters will be replaced by buffer output.
     example_view_post: Will be executed after database executions. All output data will be replaced by buffer output.
     example_view_finish: Will be executed before hook execution finishes.
   - add:
     example_add_start: Will be executed at hook start. All received parameters will be replaced by buffer output.
     example_add_validate: Will be executed at input data validation. Buffer will contain an array with variable name as ID and content as error message.
     example_add_sanitize: Will be executed after data validation. All received parameters will be replaced by buffer output.
     example_add_pre: Will be executed after hook sanitize parameters and before any database interaction.
     example_add_post: Will be executed after hook executed database interactions.
     example_add_notify: Will be executed if there's any notification to Asterisk servers. Buffer will be the notification array.
     example_add_finish: Will be executed before hook execution finishes.
   - edit:
     example_edit_start: Will be executed at hook start. All received parameters will be replaced by buffer output.
     example_edit_validate: Will be executed at input data validation. Buffer will contain an array with variable name as ID and content as error message.
     example_edit_sanitize: Will be executed after data validation. All received parameters will be replaced by buffer output.
     example_edit_pre: Will be executed after hook sanitize parameters and before any database interaction.
     example_edit_post: Will be executed after hook executed database interactions.
     example_edit_notify: Will be executed if there's any notification to Asterisk servers. Buffer will be the notification array.
     example_edit_finish: Will be executed before hook execution finishes.
   - remove:
     example_remove_start: Will be executed at hook start. All received parameters will be replaced by buffer output.
     example_remove_validate: Will be executed at input data validation. Buffer will contain an array with variable name as ID and content as error message.
     example_remove_sanitize: Will be executed after data validation. All received parameters will be replaced by buffer output.
     example_remove_pre: Will be executed after hook sanitize parameters and before any database interaction.
     example_remove_post: Will be executed after hook executed database interactions.
     example_remove_notify: Will be executed if there's any notification to Asterisk servers. Buffer will be the notification array.
     example_remove_finish: Will be executed before hook execution finishes.

Q) What structure is used in received API events?
A) All API calls will receive an array as parameter, with the structure:
   Array (
     **All received parameters**,
     "api" => Array (
       "path" => **Original request path**,
       "route" => **Original request route**,
       "hook" => **Matched hook**,
       "parameters" => **Original GET/POST parameters**,
       "vars" => **Variables from path**,
       "routevars" => **Variables from hook options**
     )
   )
