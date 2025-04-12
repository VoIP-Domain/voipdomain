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
 * VoIP Domain debug module WebUI. This is an internal debug module, and should
 * NEVER be loaded at production environment.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Debug
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add basic framework hooks, with the relative function.
 */
framework_add_hook ( "debug_exec_hook", "debug_exec_hook");
framework_add_path ( "/debug/exec_hook/:hook", "debug_exec_hook", array ( "exactonly" => false, "unauthenticated" => true));
framework_add_hook ( "debug_exec_filter", "debug_exec_filter");
framework_add_path ( "/debug/exec_filter/:filter", "debug_exec_filter", array ( "exactonly" => false, "unauthenticated" => true));
framework_add_hook ( "debug_dump_hooks", "debug_dump_hooks");
framework_add_path ( "/debug/dump_hooks", "debug_dump_hooks", array ( "unauthenticated" => true));
framework_add_hook ( "debug_dump_paths", "debug_dump_paths");
framework_add_path ( "/debug/dump_paths", "debug_dump_paths", array ( "unauthenticated" => true));
framework_add_hook ( "debug_dump_filters", "debug_dump_filters");
framework_add_path ( "/debug/dump_filters", "debug_dump_filters", array ( "unauthenticated" => true));
framework_add_hook ( "debug_get_extension", "debug_get_extension");
framework_add_path ( "/debug/get_extension/:extension", "debug_get_extension", array ( "exactonly" => false, "unauthenticated" => true));
framework_add_hook ( "debug_dup_i18n", "debug_dup_i18n");
framework_add_path ( "/debug/dup_i18n", "debug_dup_i18n", array ( "exactonly" => false, "unauthenticated" => true));
framework_add_hook ( "debug_e164", "debug_e164");
framework_add_path ( "/debug/e164", "debug_e164", array ( "unauthenticated" => true));
framework_add_hook ( "ping", "ping", IN_HOOK_INSERT_FIRST);
framework_add_path ( "/ping", "ping", array ( "unauthenticated" => true, "exactonly" => false));

/**
 * Function to return an user ping.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ping ( $buffer, $parameters)
{
  /**
   * Set page title
   */
  sys_set_title ( __ ( "Debug"));
  sys_set_subtitle ( __ ( "ping"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Debug"))
  ));

  /**
   * Create page code
   */
  $output = "<div id=\"pingpongdiv\"><canvas id=\"pingpong\"></canvas></div>\n";
  $output .= "<audio preload=\"true\" id=\"collide\">\n";
  $output .= "  <source src=\"/modules/debug/metal.mp3\" />\n";
  $output .= "  <source src=\"/modules/debug/metal.wav\" />\n";
  $output .= "</audio>\n";

  /**
   * Add ping pong JavaScript code (code from https://github.com/Idnan/pong)
   */
  sys_addjs ( "( function ()\n" .
              "{\n" .
              "  // Expand ping pong canvas parent to use full height\n" .
              "  document.getElementById ( 'pingpongdiv').style.width = '100%';\n" .
              "  document.getElementById ( 'pingpongdiv').style.height = ( document.getElementsByClassName ( 'main-footer')[0].getBoundingClientRect ().top + ( window.pageYOffset || document.documentElement.scrollTop) - document.getElementById ( 'pingpongdiv').getBoundingClientRect ().top + ( window.pageYOffset || document.documentElement.scrollTop)) + 'px';\n " .
              "\n" .
              "  // RequestAnimFrame ==> a browser API for getting smooth animations\n" .
              "  window.requestAnimFrame = ( function ()\n" .
              "  {\n" .
              "    return window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.oRequestAnimationFrame || window.msRequestAnimationFrame || function ( callback)\n" .
              "    {\n" .
              "      return window.setTimeout ( callback, 1000 / 60);\n" .
              "    };\n" .
              "  }) ();\n" .
              "  window.cancelRequestAnimFrame = ( function ()\n" .
              "  {\n" .
              "    return window.cancelAnimationFrame || window.webkitCancelRequestAnimationFrame || window.mozCancelRequestAnimationFrame || window.oCancelRequestAnimationFrame || window.msCancelRequestAnimationFrame || clearTimeout;\n" .
              "  }) ();\n" .
              "\n" .
              "  // Initialize canvas and required variables\n" .
              "  var canvas = document.getElementById ( 'pingpong'),\n" .
              "      ctx = canvas.getContext ( '2d'),  // Create canvas context\n" .
              "      T = canvas.getBoundingClientRect ().top + ( window.pageYOffset || document.documentElement.scrollTop),      // Canvas top\n" .
              "      L = canvas.getBoundingClientRect ().left + ( window.pageXOffset || document.documentElement.scrollLeft),    // Canvas left\n" .
              "      W = document.getElementById ( 'pingpongdiv').offsetWidth - 30,                                              // Canvas width\n" .
              "      H = document.getElementById ( 'pingpongdiv').offsetHeight - 20,                                             // Canvas height\n" .
              "      ball = {},                        // Ball object\n" .
              "      paddles = [2],                    // Array containing two paddles\n" .
              "      mouse = {},                       // Mouse object to store it's current position\n" .
              "      points = 0,                       // Varialbe to store points\n" .
              "      startBtn = {},                    // Start button object\n" .
              "      restartBtn = {},                  // Restart button object\n" .
              "      over = 0,                         // flag varialbe, cahnged when the game is over\n" .
              "      init,                             // variable to initialize animation\n" .
              "      paddleHit;\n" .
              "\n" .
              "  // Add mousemove and mousedown events to the canvas\n" .
              "  canvas.addEventListener ( 'mousemove', trackPosition, true);\n" .
              "  canvas.addEventListener ( 'mousedown', btnClick, true);\n" .
              "\n" .
              "  // Initialise the collision sound\n" .
              "  collision = document.getElementById ( 'collide');\n" .
              "\n" .
              "  // Set the canvas height and width to full parent size\n" .
              "  canvas.style.width = '100%';\n" .
              "  canvas.style.height = '100%';\n" .
              "  canvas.width = W;\n" .
              "  canvas.height = H;\n" .
              "\n" .
              "  // Function to paint canvas\n" .
              "  function paintCanvas ()\n" .
              "  {\n" .
              "    ctx.fillStyle = 'black';\n" .
              "    ctx.fillRect ( 0, 0, W, H);\n" .
              "  }\n" .
              "\n" .
              "  // Function for creating paddles\n" .
              "  function Paddle ( pos)\n" .
              "  {\n" .
              "    // Height and width\n" .
              "    this.h = 5;\n" .
              "    this.w = 150;\n" .
              "\n" .
              "    // Paddle's position\n" .
              "    this.x = W / 2 - this.w / 2;\n" .
              "    this.y = ( pos == 'top') ? 0 : H - this.h;\n" .
              "  }\n" .
              "\n" .
              "  // Push two new paddles into the paddles[] array\n" .
              "  paddles.push ( new Paddle ( 'bottom'));\n" .
              "  paddles.push ( new Paddle ( 'top'));\n" .
              "\n" .
              "  // Ball object\n" .
              "  ball =\n" .
              "  {\n" .
              "    x: 50,\n" .
              "    y: 50,\n" .
              "    r: 5,\n" .
              "    c: 'white',\n" .
              "    vx: 4,\n" .
              "    vy: 8,\n" .
              "\n" .
              "    // Function for drawing ball on canvas\n" .
              "    draw: function ()\n" .
              "    {\n" .
              "      ctx.beginPath ();\n" .
              "      ctx.fillStyle = this.c;\n" .
              "      ctx.arc ( this.x, this.y, this.r, 0, Math.PI * 2, false);\n" .
              "      ctx.fill ();\n" .
              "    }\n" .
              "  };\n" .
              "\n" .
              "  // Start Button object\n" .
              "  startBtn =\n" .
              "  {\n" .
              "    w: 100,\n" .
              "    h: 50,\n" .
              "    x: W / 2 - 50,\n" .
              "    y: H / 2 - 25,\n" .
              "\n" .
              "    draw: function ()\n" .
              "    {\n" .
              "      ctx.strokeStyle = 'white';\n" .
              "      ctx.lineWidth = '2';\n" .
              "      ctx.strokeRect ( this.x, this.y, this.w, this.h);\n" .
              "\n" .
              "      ctx.font = '18px Arial, sans-serif';\n" .
              "      ctx.textAlign = 'center';\n" .
              "      ctx.textBaseline = 'middle';\n" .
              "      ctx.fillStlye = 'white';\n" .
              "      ctx.fillText ( '" . __ ( "Start") . "', W / 2, H / 2);\n" .
              "    }\n" .
              "  };\n" .
              "\n" .
              "  // Restart Button object\n" .
              "  restartBtn =\n" .
              "  {\n" .
              "    w: 100,\n" .
              "    h: 50,\n" .
              "    x: W / 2 - 50,\n" .
              "    y: H / 2 - 50,\n" .
              "\n" .
              "    draw: function ()\n" .
              "    {\n" .
              "      ctx.strokeStyle = 'white';\n" .
              "      ctx.lineWidth = '2';\n" .
              "      ctx.strokeRect ( this.x, this.y, this.w, this.h);\n" .
              "\n" .
              "      ctx.font = '18px Arial, sans-serif';\n" .
              "      ctx.textAlign = 'center';\n" .
              "      ctx.textBaseline = 'middle';\n" .
              "      ctx.fillStlye = 'white';\n" .
              "      ctx.fillText ( '" . __ ( "Restart") . "', W / 2, H / 2 - 25);\n" .
              "    }\n" .
              "  };\n" .
              "\n" .
              "  // Function for creating particles object\n" .
              "  function createParticles ( x, y, m)\n" .
              "  {\n" .
              "    this.x = x || 0;\n" .
              "    this.y = y || 0;\n" .
              "\n" .
              "    this.radius = 1.2;\n" .
              "\n" .
              "    this.vx = -1.5 + Math.random () * 3;\n" .
              "    this.vy = m * Math.random () * 1.5;\n" .
              "  }\n" .
              "\n" .
              "  // Draw everything on canvas\n" .
              "  function draw ()\n" .
              "  {\n" .
              "    paintCanvas ();\n" .
              "    for ( var i = 0; i < paddles.length; i++)\n" .
              "    {\n" .
              "      p = paddles[i];\n" .
              "\n" .
              "      ctx.fillStyle = 'white';\n" .
              "      ctx.fillRect ( p.x, p.y, p.w, p.h);\n" .
              "    }\n" .
              "\n" .
              "    ball.draw ();\n" .
              "    update ();\n" .
              "  }\n" .
              "\n" .
              "  // Function to increase speed after every 5 points\n" .
              "  function increaseSpd ()\n" .
              "  {\n" .
              "    if ( points % 4 == 0)\n" .
              "    {\n" .
              "      if ( Math.abs ( ball.vx) < 15)\n" .
              "      {\n" .
              "        ball.vx += ( ball.vx < 0) ? - 1 : 1;\n" .
              "        ball.vy += ( ball.vy < 0) ? - 2 : 2;\n" .
              "      }\n" .
              "    }\n" .
              "  }\n" .
              "\n" .
              "  // Track the position of mouse cursor\n" .
              "  function trackPosition ( e)\n" .
              "  {\n" .
              "    mouse.x = e.pageX - L;\n" .
              "    mouse.y = e.pageY - T;\n" .
              "  }\n" .
              "\n" .
              "  // Function to update positions, score and everything.\n" .
              "  function update ()\n" .
              "  {\n" .
              "    // Update scores\n" .
              "    updateScore ();\n" .
              "\n" .
              "    // Move the paddles on mouse move\n" .
              "    if ( mouse.x && mouse.y)\n" .
              "    {\n" .
              "      for ( var i = 1; i < paddles.length; i++)\n" .
              "      {\n" .
              "        p = paddles[i];\n" .
              "        p.x = mouse.x - p.w / 2;\n" .
              "      }\n" .
              "    }\n" .
              "\n" .
              "    // Move the ball\n" .
              "    ball.x += ball.vx;\n" .
              "    ball.y += ball.vy;\n" .
              "\n" .
              "    // Collision with paddles\n" .
              "    p1 = paddles[1];\n" .
              "    p2 = paddles[2];\n" .
              "\n" .
              "    // If the ball strikes with paddles,\n" .
              "    // invert the y-velocity vector of ball,\n" .
              "    // increment the points, play the collision sound\n" .
              "    if ( collides ( ball, p1))\n" .
              "    {\n" .
              "      collideAction ( ball, p1);\n" .
              "    } else {\n" .
              "      if ( collides ( ball, p2))\n" .
              "      {\n" .
              "        collideAction ( ball, p2);\n" .
              "      } else {\n" .
              "        // Collide with walls, If the ball hits the top/bottom,\n" .
              "        // walls, run gameOver() function\n" .
              "        if ( ball.y + ball.r > H)\n" .
              "        {\n" .
              "          ball.y = H - ball.r;\n" .
              "          gameOver ();\n" .
              "        } else {\n" .
              "          if ( ball.y < 0)\n" .
              "          {\n" .
              "            ball.y = ball.r;\n" .
              "            gameOver ();\n" .
              "          }\n" .
              "        }\n" .
              "\n" .
              "        // If ball strikes the vertical walls, invert the\n" .
              "        // x-velocity vector of ball\n" .
              "        if ( ball.x + ball.r > W)\n" .
              "        {\n" .
              "          ball.vx = -ball.vx;\n" .
              "          ball.x = W - ball.r;\n" .
              "        } else {\n" .
              "          if ( ball.x - ball.r < 0)\n" .
              "          {\n" .
              "            ball.vx = -ball.vx;\n" .
              "            ball.x = ball.r;\n" .
              "          }\n" .
              "        }\n" .
              "      }\n" .
              "    }\n" .
              "  }\n" .
              "\n" .
              "  // Function to check collision between ball and one of the paddles\n" .
              "  function collides ( b, p)\n" .
              "  {\n" .
              "    if ( b.x + ball.r >= p.x && b.x - ball.r <= p.x + p.w)\n" .
              "    {\n" .
              "      if ( b.y >= ( p.y - p.h) && p.y > 0)\n" .
              "      {\n" .
              "        paddleHit = 1;\n" .
              "        return true;\n" .
              "      } else {\n" .
              "        if ( b.y <= p.h && p.y == 0)\n" .
              "        {\n" .
              "          paddleHit = 2;\n" .
              "          return true;\n" .
              "        } else {\n" .
              "          return false;\n" .
              "        }\n" .
              "      }\n" .
              "    }\n" .
              "  }\n" .
              "\n" .
              "  // Do this when collides == true\n" .
              "  function collideAction ( ball, p)\n" .
              "  {\n" .
              "    ball.vy = -ball.vy;\n" .
              "\n" .
              "    if ( paddleHit == 1)\n" .
              "    {\n" .
              "      ball.y = p.y - p.h;\n" .
              "      multiplier = -1;\n" .
              "    } else {\n" .
              "      if ( paddleHit == 2)\n" .
              "      {\n" .
              "        ball.y = p.h + ball.r;\n" .
              "        multiplier = 1;\n" .
              "      }\n" .
              "    }\n" .
              "\n" .
              "    points++;\n" .
              "    increaseSpd ();\n" .
              "\n" .
              "    if ( collision)\n" .
              "    {\n" .
              "      if ( points > 0)\n" .
              "      {\n" .
              "        collision.pause ();\n" .
              "      }\n" .
              "\n" .
              "      collision.currentTime = 0;\n" .
              "      collision.play ();\n" .
              "    }\n" .
              "  }\n" .
              "\n" .
              "  // Function for updating score\n" .
              "  function updateScore ()\n" .
              "  {\n" .
              "    ctx.fillStlye = 'white';\n" .
              "    ctx.font = '16px Arial, sans-serif';\n" .
              "    ctx.textAlign = 'left';\n" .
              "    ctx.textBaseline = 'top';\n" .
              "    ctx.fillText ( '" . __ ( "Score") . ": ' + points, 20, 20);\n" .
              "  }\n" .
              "\n" .
              "  // Function to run when the game overs\n" .
              "  function gameOver ()\n" .
              "  {\n" .
              "    ctx.fillStlye = 'white';\n" .
              "    ctx.font = '20px Arial, sans-serif';\n" .
              "    ctx.textAlign = 'center';\n" .
              "    ctx.textBaseline = 'middle';\n" .
              "    ctx.fillText ( '" . __ ( "Game Over - You scored") . " ' + points + ' " . __ ( "points!") . "', W / 2, H / 2 + 25);\n" .
              "\n" .
              "    // Stop the Animation\n" .
              "    cancelRequestAnimFrame ( init);\n" .
              "\n" .
              "    // Set the over flag\n" .
              "    over = 1;\n" .
              "\n" .
              "    // Show the restart button\n" .
              "    restartBtn.draw ();\n" .
              "\n" .
              "    canvas.style.cursor = 'default';\n" .
              "  }\n" .
              "\n" .
              "  // Function for running the whole animation\n" .
              "  function animloop ()\n" .
              "  {\n" .
              "    init = requestAnimFrame ( animloop);\n" .
              "    draw ();\n" .
              "  }\n" .
              "\n" .
              "  // Function to execute at startup\n" .
              "  function startScreen ()\n" .
              "  {\n" .
              "    draw ();\n" .
              "    startBtn.draw ();\n" .
              "  }\n" .
              "\n" .
              "  // On button click (Restart and start)\n" .
              "  function btnClick ( e)\n" .
              "  {\n" .
              "    canvas.style.cursor = 'none';\n" .
              "\n" .
              "    // Variables for storing mouse position on click\n" .
              "    var mx = e.pageX - L, my = e.pageY - T;\n" .
              "\n" .
              "    // Click start button\n" .
              "    if ( mx >= startBtn.x && mx <= startBtn.x + startBtn.w)\n" .
              "    {\n" .
              "      animloop ();\n" .
              "\n" .
              "      // Delete the start button after clicking it\n" .
              "      startBtn = {};\n" .
              "    }\n" .
              "\n" .
              "    // If the game is over, and the restart button is clicked\n" .
              "    if ( over == 1)\n" .
              "    {\n" .
              "      if ( mx >= restartBtn.x && mx <= restartBtn.x + restartBtn.w)\n" .
              "      {\n" .
              "        ball.x = 20;\n" .
              "        ball.y = 20;\n" .
              "        points = 0;\n" .
              "        ball.vx = 4;\n" .
              "        ball.vy = 8;\n" .
              "        animloop ();\n" .
              "\n" .
              "        over = 0;\n" .
              "      }\n" .
              "    }\n" .
              "  }\n" .
              "\n" .
              "  // Show the start screen\n" .
              "  startScreen ();\n" .
              "}) ();\n");

  return $output;
}

/**
 * Function to get an extension, based on number or text.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Output of the found data
 */
function debug_get_extension ( $buffer, $parameters)
{
  /**
   * Set page title
   */
  sys_set_title ( __ ( "Debug"));
  sys_set_subtitle ( __ ( "extension dump"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Debug"))
  ));

  /**
   * Search for data
   */
  $output = "Searching for " . ( (int) $parameters["extension"] != 0 ? "number " . (int) $parameters["extension"] : "text " . $parameters["extension"]) . "<br />\n";
  $output .= "<pre>";
  $output .= print_r ( filters_call ( "get_extensions", ( (int) $parameters["extension"] != 0 ? array ( "number" => (int) $parameters["extension"]) : array ( "text" => $parameters["extension"]))), true);
  $output .= "</pre>";

  return $output;
}

/**
 * Function to execute a hook and debug it.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Output of the found data
 */
function debug_exec_hook ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Debug"));
  sys_set_subtitle ( __ ( "hook dump"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Debug"))
  ));

  /**
   * Show hook output
   */
  $output = "HTML output of hook \"" . $parameters["hook"] . "\" execution:<br />\n";
  $output .= "<pre>\n";
  $output .= str_replace ( "<", "&lt;", print_r ( framework_call ( $parameters["hook"]), true));
  $output .= "</pre>\n";
  if ( sizeof ( $_in["page"]["css"]) != 0)
  {
    $output .= "<br />\n";
    $output .= "CSS output of hook \"" . $parameters["hook"] . "\" execution:<br />\n";
    $output .= "<pre>\n";
    $output .= str_replace ( "<", "&lt;", print_r ( $_in["page"]["css"], true));
    $output .= "</pre>\n";
  }
  if ( $_in["page"]["inlinecss"] != "")
  {
    $output .= "<br />\n";
    $output .= "Inline CSS output of hook \"" . $parameters["hook"] . "\" execution:<br />\n";
    $output .= "<pre>\n";
    $output .= str_replace ( "<", "&lt;", print_r ( $_in["page"]["inlinecss"], true));
    $output .= "</pre>\n";
  }
  if ( sizeof ( $_in["page"]["js"]) != 0)
  {
    $output .= "<br />\n";
    $output .= "JavaScript output of hook \"" . $parameters["hook"] . "\" execution:<br />\n";
    $output .= "<pre>\n";
    $output .= str_replace ( "<", "&lt;", print_r ( $_in["page"]["js"], true));
    $output .= "</pre>\n";
  }
  if ( $_in["page"]["inlinejs"] != "")
  {
    $output .= "<br />\n";
    $output .= "Inline JavaScript output of hook \"" . $parameters["hook"] . "\" execution:<br />\n";
    $output .= "<pre>\n";
    $output .= str_replace ( "<", "&lt;", print_r ( $_in["page"]["inlinejs"], true));
    $output .= "</pre>\n";
  }

  /**
   * Clear JavaScript and CSS generated code
   */
  sys_clear_js ();
  sys_clear_css ();

  return $output;
}

/**
 * Function to execute a filter and debug it.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Output of the found data
 */
function debug_exec_filter ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Debug"));
  sys_set_subtitle ( __ ( "filter dump"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Debug"))
  ));

  /**
   * Show hook output
   */
  $output = "Content output of filter \"" . $parameters["filter"] . "\" execution:<br />\n";
  $output .= "<pre>\n";
  $result = filters_call ( $parameters["filter"], $parameters);
  $output .= print_r ( $result, true);
  $output .= "</pre>\n";

  return $output;
}

/**
 * Function to dump the framework hook table.
 *
 * @global array $_plugins Framework internal plugin system variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Output of the found data
 */
function debug_dump_hooks ( $buffer, $parameters)
{
  global $_plugins;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Debug"));
  sys_set_subtitle ( __ ( "hook structure dump"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Debug"))
  ));

  /**
   * Show hook structure
   */
  $output = "Framework hook structure:<br />\n";
  $output .= "<pre>\n";
  $output .= print_r ( $_plugins, true);
  $output .= "</pre>\n";

  return $output;
}

/**
 * Function to dump the framework paths table.
 *
 * @global array $_paths Framework internal plugin system variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Output of the found data
 */
function debug_dump_paths ( $buffer, $parameters)
{
  global $_paths;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Debug"));
  sys_set_subtitle ( __ ( "paths structure dump"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Debug"))
  ));

  /**
   * Show hook structure
   */
  $output = "Framework paths structure:<br />\n";
  $output .= "<pre>\n";
  $output .= print_r ( $_paths, true);
  $output .= "</pre>\n";

  return $output;
}

/**
 * Function to dump the framework filters table.
 *
 * @global array $_filters Framework internal filters system variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Output of the found data
 */
function debug_dump_filters ( $buffer, $parameters)
{
  global $_filters;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Debug"));
  sys_set_subtitle ( __ ( "filters structure dump"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Debug"))
  ));

  /**
   * Show hook structure
   */
  $output = "Framework filters structure:<br />\n";
  $output .= "<pre>\n";
  $output .= print_r ( $_filters, true);
  $output .= "</pre>\n";

  return $output;
}

/**
 * Function to create E.164 debug page.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Output of the found data
 */
function debug_e164 ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Debug"));
  sys_set_subtitle ( __ ( "E.164 number debug"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Debug"))
  ));

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "jquery-json-viewer-switch", "src" => "/vendors/jquery-json-viewer/json-viewer/jquery.json-viewer.css", "dep" => array ()));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "jquery-json-viewer", "src" => "/vendors/jquery-json-viewer/json-viewer/jquery.json-viewer.js", "dep" => array ()));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"debug_e164\">\n";

  // Add debug number field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"block_add_description\" class=\"control-label col-xs-2\">" . __ ( "Number") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"number\" class=\"form-control\" id=\"debug_number\" placeholder=\"" . __ ( "Number (E.164 format)") . "\" maxlength=\"16\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <button class=\"btn btn-primary add ladda-button\" data-style=\"expand-left\">" . __ ( "Search") . "</button>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Finish form
  $output .= "</form>\n";

  // Result field
  $output .= "<div id=\"debug_e164_result\" style=\"border: 1px solid; width: 100%; height: 5em; display: inline-table; padding-left: 16px\"></div>\n";

  /**
   * Add add form JavaScript code
   */
  sys_addjs ( "$('#debug_e164').on ( 'submit', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  $('#debug_e164_result').html ( '');\n" .
              "  VoIP.rest ( '/debug/explain/' + encodeURIComponent ( $('#debug_number').val ()), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "  {\n" .
              "    $('#debug_e164_result').jsonViewer ( data, { collapsed: false, withQuotes: true});\n" .
              "  }).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "  {\n" .
              "    new PNotify ( { title: '" . __ ( "E.164 debug") . "', text: '" . __ ( "Error requesting number debug!") . "', type: 'error'});\n" .
              "  });\n" .
              "});\n");

  return $output;
}

/**
 * Function to check for duplicated internationalization strings between modules.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Output of the found data
 */
function debug_dup_i18n ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Debug"));
  sys_set_subtitle ( __ ( "duplicated internationalization entries"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Debug"))
  ));

  /**
   * Show hook structure
   */
  $output = "Duplicated internationalization strings:<br />\n";
  $output .= "<pre>";

  /**
   * Check for each string
   */
  foreach ( $_in["i18n"] as $text => $array)
  {
    $dup = array_unique ( array_diff_assoc ( $_in["i18n"][$text], array_unique ( $_in["i18n"][$text])));
    if ( sizeof ( $dup) == 1)
    {
      $dup = reset ( $dup);
      $output .= $text . ": ";
      foreach ( $_in["i18n"][$text] as $key => $value)
      {
        if ( $value == $dup)
        {
          $output .= $key . ", ";
        }
      }
      $output = substr ( $output, 0, strlen ( $output) - 2) . "\n";
    }
  }
  $output .= "</pre>";

  return $output;
}
?>
