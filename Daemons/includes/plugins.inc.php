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
 * Library with functions to add modularity to the VoIP Domain framework. This
 * function library has a deep concept based on WordPress pluggable system.
 * Basically it's the same idea that's implemented, but trying to create an
 * interface that's highly based on method calls, even the most internal function
 * and basic interface calls.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Core
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Start global plugin modularity variable
 *
 * @var array $_plugins Global variable containing the plugin system hooks,
 *                      functions list, buffers, and other variables.
 */
if ( ! isset ( $_plugins) || ! is_array ( $_plugins))
{
  $_plugins = array ();
}
if ( ! array_key_exists ( "hooks", $_plugins) || ! is_array ( $_plugins["hooks"]))
{
  $_plugins["hooks"] = array ();
}
if ( ! array_key_exists ( "hooksdocs", $_plugins) || ! is_array ( $_plugins["hooksdocs"]))
{
  $_plugins["hooksdocs"] = array ();
}
if ( ! array_key_exists ( "buffers", $_plugins) || ! is_array ( $_plugins["buffers"]))
{
  $_plugins["buffers"] = array ();
}
if ( ! array_key_exists ( "map", $_plugins) || ! is_array ( $_plugins["map"]))
{
  $_plugins["map"] = array ();
}
if ( ! array_key_exists ( "current", $_plugins) || ! $_plugins["current"])
{
  $_plugins["current"] = array ();
}
if ( ! array_key_exists ( "functions", $_plugins) || ! $_plugins["functions"])
{
  $_plugins["functions"] = array ();
}

/**
 * Define variables
 */
define ( "IN_HOOK_NULL", 0);
define ( "IN_HOOK_INSERT_FIRST", 1);
define ( "IN_HOOK_REMOVE_OTHERS", 2);
define ( "IN_HOOK_REPLACE", 4);
define ( "IN_HOOK_INSERT_BEFORE", 8);
define ( "IN_HOOK_INSERT_AFTER", 16);

/**
 * Function to add a function call to a hook. This is the main function of this
 * modular plugin system. You basically register a hook (which is a string you
 * choice, i.e. validate_user), and associate with a function. When you fire
 * the execution of this hook, the plugin system will call each function
 * associated with this hook, in order. So, you can write your system using
 * hook calls, and add plugins adding functions to your system hooks. This
 * will enable you to make a modular system and a pluggable system, enabling
 * other developers to add code without modifying the original code, and the
 * plugin could be enabled and disabled easily.
 *
 * @global array $_plugins The main plugin system variable
 * @global array $_in Framework global configuration variable
 * @param string $hook The name of the hook to add the function
 * @param string $function The name of the function to be called
 * @param int[optional] $options Options. Please refer to defined constants
 * @param array[optional] $documentation The documentation array
 * @return void
 */
function framework_add_hook ( $hook, $function, $options = 0, $documentation = array ())
{
  global $_plugins, $_in;

  if ( array_key_exists ( $hook, $_plugins["hooks"]) && is_array ( $_plugins["hooks"][$hook]) && in_array ( $function, $_plugins["hooks"][$hook]))
  {
    framework_error ( "IntelliNews Framework: Function \"" . $function . "\" already exist at hook \"" . $hook . "\"");
    return;
  }
  if ( $options & IN_HOOK_REMOVE_OTHERS)
  {
    unset ( $_plugins["hooks"][$hook]);
  }
  if ( ! array_key_exists ( $hook, $_plugins["hooks"]) || ! is_array ( $_plugins["hooks"][$hook]))
  {
    $_plugins["hooks"][$hook] = array ();
  }
  if ( $options & IN_HOOK_INSERT_FIRST)
  {
    array_unshift ( $_plugins["hooks"][$hook], $function);
  } else {
    array_push ( $_plugins["hooks"][$hook], $function);
  }
  if ( array_key_exists ( $function, $_plugins["functions"]))
  {
    $var1 = is_array ( $_plugins["functions"][$function]) ? $_plugins["functions"][$function] : array ();
    $var2 = is_array ( $documentation) ? $documentation : array ();
    $_plugins["functions"][$function] = array_merge_recursive_distinct ( $var1, $var2);
  } else {
    $_plugins["functions"][$function] = is_array ( $documentation) ? $documentation : array ();
  }
  $_plugins["map"][$function] = $_in["module"];
}

/**
 * Function to add extra documentation to a hook. This is usefull when your hook
 * call's other hoooks, that's not directly associated to your main hook.
 *
 * global array $_plugins The main plugin system variable
 * @param string $function The name of the function
 * @param array $documentation The documentation array
 * @return void
 */
function framework_add_function_documentation ( $function, $documentation)
{
  global $_plugins;

  if ( array_key_exists ( $function, $_plugins["functions"]))
  {
    $var1 = is_array ( $_plugins["functions"][$function]) ? $_plugins["functions"][$function] : array ();
    $var2 = is_array ( $documentation) ? $documentation : array ();
    $_plugins["functions"][$function] = array_merge_recursive_distinct_with_sequencial ( $var1, $var2);
  } else {
    $_plugins["functions"][$function] = is_array ( $documentation) ? $documentation : array ();
  }
}

/**
 * Function to add documentation component. This is usefull to create
 * documentation component schemas, pointing complex objects reponses to one
 * documentation schema.
 *
 * global array $_plugins The main plugin system variable
 * @param string $component The name of the component
 * @param array $documentation The object documentation array
 * @return void
 */
function framework_add_component_documentation ( $component, $documentation)
{
  global $_plugins;

  if ( ! array_key_exists ( "components", $_plugins))
  {
    $_plugins["components"] = array ();
  }
  if ( array_key_exists ( $component, $_plugins["components"]))
  {
    $var1 = is_array ( $_plugins["components"][$component]) ? $_plugins["components"][$component] : array ();
    $var2 = is_array ( $documentation) ? $documentation : array ();
    $_plugins["components"][$component] = array_merge_recursive_distinct_with_sequencial ( $var1, $var2);
  } else {
    $_plugins["components"][$component] = is_array ( $documentation) ? $documentation : array ();
  }
}

/**
 * Function to add a function call to a hook. This is a similar function to
 * framework_add_hook, but you provide a reference and an option to replace,
 * add before or add after the referenced function. You could use this function
 * to add a function call at a specific position of the hook, to keep the order
 * you want. Please refer to framework_add_hook function documentation for
 * more information.
 *
 * @global array $_plugins The main plugin system variable
 * @global array $_in Framework global configuration variable
 * @param string $hook The name of the hook to add the function
 * @param string $function The name of the function to be called
 * @param string $reference The name of the function to use as reference
 * @param int[optional] $options Options. Please refer to defined constants
 * @return boolean
 */
function framework_add_hook_at ( $hook, $function, $reference, $options = IN_HOOK_INSERT_AFTER)
{
  global $_plugins, $_in;

  if ( is_array ( $_plugins["hooks"][$hook]) && in_array ( $function, $_plugins["hooks"][$hook]))
  {
    framework_error ( "IntelliNews Framework: Function \"" . $function . "\" already exist at hook \"" . $hook . "\"");
    return;
  }
  if ( ! in_array ( $reference, $_plugins["hooks"][$hook]))
  {
    framework_error ( "IntelliNews Framework: Reference function \"" . $reference . "\" doesn't exist at hook \"" . $hook . "\"");
    return;
  }
  $_plugins["map"][$function] = $_in["module"];
  if ( $options & IN_HOOK_REPLACE)
  {
    $_plugins["hooks"][$hook][array_search ( $reference, $_plugins["hooks"][$hook], true)] = $function;
    return;
  }
  if ( $options & IN_HOOK_INSERT_BEFORE)
  {
    array_splice ( $_plugins["hooks"][$hook], array_search ( $reference, $_plugins["hooks"][$hook], true), 0, $function);
  } else {
    array_splice ( $_plugins["hooks"][$hook], array_search ( $reference, $_plugins["hooks"][$hook], true) + 1, 0, $function);
  }
}

/**
 * Function to remove a function from a hook. The function returns false if hook
 * or function was not found, or true if removed correctly.
 *
 * @global array $_plugins The main plugin system variable
 * @param string $hook The name of the hook to be checked
 * @param string $function The name of the function to be removed
 * @return boolean
 */
function framework_remove_hook_function ( $hook, $function)
{
  global $_plugins;

  if ( ! is_array ( $_plugins["hooks"][$hook]))
  {
    framework_error ( "IntelliNews Framework: Hook \"" . $hook . "\" not found to remove function \"" . $function . "\".");
    return;
  }
  if ( ! in_array ( $function, $_plugins["hooks"][$hook]))
  {
    return false;
  }
  $_plugins["hooks"][$hook] = array_diff ( $_plugins["hooks"][$hook], array ( $function));
  unset ( $_plugins["map"][$function]);
  return true;
}

/**
 * Function to check if a hook exist (optionally, if there's a registered function).
 *
 * @global array $_plugins The main plugin system variable
 * @param string $hook The name of the hook to be checked
 * @param string $function[optional] The name of the function to be checked
 * @return boolean
 */
function framework_has_hook ( $hook, $function = "")
{
  global $_plugins;

  if ( $function != "")
  {
    return array ( $function, $_plugins["hooks"][$hook]);
  } else {
    return array_key_exists ( $hook, $_plugins["hooks"]);
  }
}

/**
 * Function to remove a hook.
 *
 * @global array $_plugins The main plugin system variable
 * @param string $hook The name of the hook to be checked
 * @return boolean
 */
function framework_remove_hook ( $hook)
{
  global $_plugins;

  if ( ! in_array ( $function, $_plugins["hooks"][$hook]))
  {
    return false;
  }
  foreach ( $_plugins["hooks"][$hook] as $function)
  {
    unset ( $_plugins["map"][$function]);
  }
  unset ( $_plugins["hooks"][$hook]);
  unset ( $_plugins["buffers"][$hook]);

  return true;
}

/**
 * Function to retrieve current buffer from a hook call. Every hook has an
 * internal buffer that's passed from function to function call, and can be
 * retrieve with this function. The content type may vary depending on the
 * function execution return.
 *
 * @global array $_plugins The main plugin system variable
 * @param string $hook The name of the hook to be checked
 * @return mixed
 */
function framework_get_buffer ( $hook)
{
  global $_plugins;

  return $_plugins["buffers"][$hook];
}

/**
 * Function to clear a hook call buffer.
 *
 * @global array $_plugins The main plugin system variable
 * @param string $hook The name of the hook to be cleared
 * @return void
 */
function framework_clear_buffer ( $hook)
{
  global $_plugins;

  unset ( $_plugins["buffers"][$hook]);
}

/**
 * Function to execute the hook call. This is the main plugin system function,
 * that make the call for each function associated with the hook, passing the
 * content of the called function to another as a parameter. There's an optional
 * parameter that can be passed to all function calls, and optinally, print the
 * content output if third parameter set to true.
 *
 * @global array $_plugins The main plugin system variable
 * @global array $_in Framework global configuration variable
 * @param string $hook The name of the hook to be called
 * @param array[optional] Array containing the parameters to be passed to the
 *                        called functions of the hook
 * @param boolean[optional] Set true to print the buffer output at last function
 *                          call
 * @return mixed
 */
function framework_call ( $hook, $parameters = array (), $output = false, $buffer = "")
{
  global $_plugins, $_in;

  /**
   * Check if hook exists
   */
  if ( ! array_key_exists ( $hook, $_plugins["hooks"]))
  {
    framework_error ( "IntelliNews Framework: Cannot find hook \"" . $hook . "\" to call");
    return false;
  }

  /**
   * Check for plugin loop
   */
  if ( array_key_exists ( $hook, $_plugins["current"]) && is_array ( $_plugins["current"][$hook]))
  {
    framework_error ( "IntelliNews Framework: Loop detected at hook \"" . $hook . "\"");
    return false;
  }

  /**
   * Call each registered function at the hook
   */
  if ( ! array_key_exists ( $hook, $_plugins["buffers"]))
  {
    $_plugins["buffers"][$hook] = $buffer;
  }
  foreach ( $_plugins["hooks"][$hook] as $function)
  {
    if ( ! function_exists ( $function))
    {
      if ( ! is_loaded ( $_plugins["map"][$function], "module"))
      {
        if ( is_readable ( dirname ( __FILE__) . "/../modules/" . $_plugins["map"][$function] . "/filter.php"))
        {
          require_once ( dirname ( __FILE__) . "/../modules/" . $_plugins["map"][$function] . "/filter.php");
        }
        if ( is_readable ( dirname ( __FILE__) . "/../modules/" . $_plugins["map"][$function] . "/module.php"))
        {
          require_once ( dirname ( __FILE__) . "/../modules/" . $_plugins["map"][$function] . "/module.php");
        }
      }
      if ( ! function_exists ( $function))
      {
        framework_error ( "IntelliNews Framework: Registered function \"" . $function . "\" doesn't exist at hook \"" . $hook . "\"");
        continue;
      }
    }
    $_in["module"] = $_plugins["map"][$function];
    $_plugins["current"][$hook][] = $function;
    $_plugins["buffers"][$hook] = call_user_func ( $function, $_plugins["buffers"][$hook], $parameters);
  }
  unset ( $_plugins["current"][$hook]);
  $_in["module"] = "";

  /**
   * Output returned hook processing content if requested
   */
  if ( $output)
  {
    echo $_plugins["buffers"][$hook];
  }

  /**
   * Return hook processing content
   */
  $return = $_plugins["buffers"][$hook];
  unset ( $_plugins["buffers"][$hook]);
  return $return;
}

/**
 * Internal function to print error messages using the PHP error processor.
 *
 * @param string Message to be printed
 * @param int[optional] PHP error trigger level
 * @return void
 */
function framework_error ( $message, $level = E_USER_NOTICE)
{
  $caller = @next ( debug_backtrace ());
  trigger_error ( $message . " in <strong>" . $caller["file"] . "</strong> on line <strong>" . $caller["line"] . "</strong> with error handler", $level);
}

/**
 * Internal function to check if a module was loaded.
 *
 * @param string Module name
 * @param string Module type
 * @return boolean
 */
function is_loaded ( $module, $type)
{
  foreach ( get_included_files () as $included)
  {
    if ( strpos ( $included, "/modules/" . $module . "/" . $type . ".php") !== false)
    {
      return true;
    }
  }
  return false;
}
?>
