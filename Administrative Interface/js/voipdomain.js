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

/**
 * VoIP Domain main JavaScript framework control script. This script implements
 * all JavaScript code needed to interface to work.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@intellinews.com.br>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Core
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

var VoIP = ( function ()
{
  // Private variables:
  var modules = {};
  var paths = {};
  var lastpath = {};
  var events =
  {
    'session_timeout': function ( data)
                       {
                         location.reload ();
                         return false;
                       }
  };
  var apiurl = '/api';
  var title = 'VoIP Domain';
  var user = '';
  var name = '';
  var uid = '';

  // About this library:
  this.about = {
    version: '1.0',
    author: 'Ernani José Camargo Azevedo'
  };

  // Public module parameters
  this.parameters = null;

  /**
   * Plugin settings
   */
  this.settings = function ( variables)
  {
    if ( 'title' in variables)
    {
      title = variables.title;
    }
    if ( 'user' in variables)
    {
      user = variables.user;
    }
    if ( 'name' in variables)
    {
      name = variables.name;
    }
    if ( 'uid' in variables)
    {
      uid = variables.uid;
    }
  };
  this.getUID = function ()
  {
    return uid;
  }
  this.getUser = function ()
  {
    return user;
  }
  this.getName = function ()
  {
    return name;
  }

  /**
   * Module manipulation functions
   */
  var module = {
    add: function ( module, data)
         {
           modules[module] = data;
         },
    del: function ( module)
         {
           delete modules[module];
         },
    getAll: function ()
            {
              return modules;
            },
    fire: function ( module, parameters)
          {
            // Remove any StickyTableHaders
            if ( typeof ( $.fn.stickyTableHeaders) == 'function')
            {
              $('table').each ( function ()
              {
                $(this).stickyTableHeaders ( 'destroy');
              });
            }

            // Remove any visible tooltip
            $('div.tooltip[id^="tooltip"]').remove ();

            // Close any open select2 container
            if ( typeof ( $.fn.select2) == 'function')
            {
              $('select.select2-hidden-accessible').select2 ( 'close');
            }

            // Close any open bootstrap modal
            $('.modal').modal ( 'hide');

            // Remove any datatable table
            if ( typeof ( $.fn.dataTable) == 'function')
            {
              $('table:not([data-dt=""]').each ( function ()
              {
                try
                {
                  $(this).data ( 'dt').destroy ( true);
                } catch ( e) {}
              });
            }

            // Stop any ladda spin
            if ( typeof ( $.ladda) == 'function')
            {
              $.ladda ( 'stopAll');
            }

            // Change page content
            $('section.content-header').fadeOut ( 'fast', function ()
            {
              $('#page_title').html ( modules[module].title);
              $('#page_subtitle').html ( ( 'subtitle' in modules[module] ? ' - ' + modules[module].subtitle : ''));
              var breadcrumb = '<li' + ( modules[module].breadcrumb.length == 0 ? ' class="active"' : '><a href="/"') + '><i class="fa fa-dashboard"></i> Início' + ( modules[module].breadcrumb.length == 0 ? '' : '</a>') + '</li>';
              for ( var x in modules[module].breadcrumb)
              {
                if ( 'link' in modules[module].breadcrumb[x])
                {
                  breadcrumb += '<li><a href="' + modules[module].breadcrumb[x].link + '">' + modules[module].breadcrumb[x].title + '</a></li>';
                } else {
                  breadcrumb += '<li class="active">' + modules[module].breadcrumb[x].title + '</li>';
                }
              }
              $('#page_breadcrumb').html ( breadcrumb);
            }).fadeIn ( 'fast');

            // Add page content
            $('#content').html ( modules[module].content);

            // Export parameters
            VoIP.parameters = parameters;

            // If there's custom JavaScript code, add it
            if ( 'inlinejs' in modules[module])
            {
              $('<script type="text/javascript">').text ( modules[module].inlinejs).appendTo ( 'body');
            }

            // Active any tooltip if exists
            setTimeout ( function () { $('#content [data-toggle="tooltip"]').tooltip (); }, 0);

            // Change to tab if specified at URL hash
            if ( location.hash && $('.nav-tabs a[href="' + location.hash + '"]').length != 0)
            {
              $('.nav-tabs a[href="' + location.hash + '"]').tab ( 'show');
            }

//            // Refresh any time with timeago if exists
//            $('#content time.timeago').timeago ();

            console.log ( 'Firing module ' + module + ' with parameters: ' + JSON.stringify ( parameters));
          },
    load: function ( path, callonload, pushonload)
          {
            callonload = ( typeof ( callonload) === 'undefined' ? false : callonload);
            pushonload = ( typeof ( pushonload) === 'undefined' ? true : pushonload);

            // If path is /, change to /dashboard
            if ( path == '/')
            {
              path = '/dashboard';
            }

            // Scroll page to top
            $('.wrapper').animate ( { scrollTop: 0}, 100);

            // Show load bar
            $('.percentage').css ( 'width', 0).css ( 'display', 'block').animate ( { width: '10%'}, 50);

            $.ajax (
            {
              url: ( path.indexOf ( '#') != -1 ? path.substring ( 0, path.indexOf ( '#')) : path),
              headers:
              {
                'X-INFramework': 'page'
              },
              async: true,
              cache: false,
              error: function ( jqXHR, textStatus, errorThrown)
              {
                console.log ( 'Error loading page: ' + jqXHR.status + ' ' + jqXHR.statusText);
                console.log ( 'Received data: ' + jqXHR.responseText);
              },
              success: function ( data, textStatus, jqXHR)
              {
                if ( typeof ( data) == 'object' && 'event' in data)
                {
                  if ( data.event == 'session_timeout')
                  {
                    if ( location.pathname == ( VoIP.lastpath.indexOf ( '#') != -1 ? VoIP.lastpath.substring ( 0, VoIP.lastpath.indexOf ( '#')) : VoIP.lastpath))
                    {
                      location.reload ();
                    } else {
                      location.href = VoIP.lastpath;
                    }
                  } else {
                    event.call ( data);
                  }
                  return;
                }
                if ( typeof ( data) != 'object' || ! ( 'index' in data))
                {
                  console.log ( 'Error loading page. Returned data: ' + JSON.stringify ( data));
                  return;
                }
                paths[data.index] = data.hook;
                modules[data.hook] = data;

                // If there's custom CSS, add it
                if ( 'inlinecss' in data)
                {
                  $('<style type="text/css">').text ( data.inlinecss).appendTo ( 'head');
                }

                // Load data using jquery.loader
                $.loader (
                {
                  js: data.js,
                  css: data.css,
                  onrefresh: function ( loaded, total, percentage)
                             {
                               $('.percentage').animate ( { width: percentage + '%'}, 50);
                             },
                  onfinish: function ()
                            {
                              // Hide percentage load bar
                              $('.percentage').fadeOut ( 'slow');

                              if ( callonload)
                              {
                                VoIP.path.call ( path, pushonload);
                              }
                            }
                });
              }
            });
          }
  };

  /**
   * System events functions
   */
  this.event = {
    add: function ( event, code)
         {
           events[event] = code;
         },
    del: function ( event)
         {
           delete events[event];
         },
    call: function ( data)
          {
            if ( typeof ( data) == 'object' && 'event' in data && typeof ( events[data.event]) == 'function')
            {
              events[data.event] ( data);
            } else {
              return false;
            }
          }
  };

  /**
   * Path call functions
   */
  this.path = {
    add: function ( index, module)
         {
           paths[index] = module;
         },
    del: function ( index)
         {
           delete paths[index];
         },
    getAll: function ()
            {
              return paths;
            },
    call: function ( path, push)
          {
            push = ( typeof ( push) === 'undefined' ? true : push);
            searchpath = ( path.indexOf ( '#') != -1 ? path.substring ( 0, path.indexOf ( '#')) : path);

            // If request is logout, destroy session and redirect
            if ( path == '/logout')
            {
              VoIP.rest ( '/sys/session', 'DELETE');
              location.href = '/';
              return;
            }

            // Set last path to new path
            VoIP.lastpath = path;

            // First, check if path has some match:
            for ( var key in paths)
            {
              var match = matchPath ( key, searchpath);
              if ( match !== false)
              {
                if ( push)
                {
                  history.pushState ( null, null, path);
                }
                module.fire ( paths[key], match);
                return;
              }
            }

            // Ok, we don't have the path and module. Try to fetch it from server:
            module.load ( path, true, push);
          }
  };

  /**
   * REST API dataTables wrapper
   */
  this.dataTables = function ( path, route, data)
  {
    var data = rest ( path, route, data);
    if ( typeof ( data.result) == 'object' && data.API.status == 'ok')
    {
      return data.result;
    } else {
      console.log ( 'VoIP API dataTables wrapper request error. Status: ' + data.API.statusCode + ' ' + data.API.statusText);
      return [];
    }
  }

  /**
   * REST API select2 wrapper
   */
  this.select2 = function ( path, route, data)
  {
    var data = rest ( path, route, data);
    if ( typeof ( data.result) == 'object' && data.API.status == 'ok')
    {
      var ret = [];
      for ( var x in data.result)
      {
        ret.push ( { id: data.result[x][0], text: data.result[x][1]});
      }
      return ret;
    } else {
      console.log ( 'VoIP API select2 wrapper request error. Status: ' + data.API.statusCode + ' ' + data.API.statusText);
      return [];
    }
  }

  /**
   * REST API connections
   */
  this.rest = function ( path, route, data)
  {
    if ( path.substring ( 0, 1) != '/')
    {
      path = '/' + path;
    }
    var result = null;
    $.ajax (
    {
      type: 'POST',
      url: apiurl + path,
      data: JSON.stringify ( data),
      async: false,
      cache: false,
      headers:
      {
        'X-INFramework': 'api',
        'X-HTTP-Method-Override': route,
        'Accept': 'application/json'
      },
      contentType: 'application/json; charset=utf-8',
      dataType: 'json',
      success: function ( data, textStatus, jqXHR)
               {
                 result = {
                   result: data,
                   API: {
                     status: 'ok',
                     content: jqXHR.responseText,
                     statusCode: jqXHR.statusCode ().status,
                     statusText: jqXHR.statusCode ().statusText,
                     headers: jqXHR.getAllResponseHeaders ()
                   }
                 };
               },
      error: function ( jqXHR, textStatus, errorThrown)
             {
               try
               {
                 var jsonresult = JSON.parse ( jqXHR.responseText);
               } catch ( e) {
                 var jsonresult = [];
               }
               if ( 'event' in jsonresult)
               {
                 if ( jsonresult.event == 'session_timeout')
                 {
                   if ( location.pathname == ( VoIP.lastpath.indexOf ( '#') != -1 ? VoIP.lastpath.substring ( 0, VoIP.lastpath.indexOf ( '#')) : VoIP.lastpath))
                   {
                     location.reload ();
                   } else {
                     location.href = VoIP.lastpath;
                   }
                 } else {
                   event.call ( jsonresult.event);
                 }
               }
               result = {
                 result: jsonresult,
                 API: {
                   status: 'error',
                   content: jqXHR.responseText,
                   statusCode: jqXHR.statusCode ().status,
                   statusText: jqXHR.statusCode ().statusText,
                   headers: jqXHR.getAllResponseHeaders ()
                 }
               };
             }
    });

    return result;
  }

  /**
   * Library functions
   */
  this.matchPath = function ( match, needle)
  {
    matches = {};
    while ( match != '')
    {
      search = match.substring ( 0, 1);
      match = match.substring ( 1);
      if ( search == ':')
      {
        key = '';
        value = '';
        // First, get value:
        if ( needle.indexOf ( '/') == -1)
        {
          value = needle;
          needle = '';
        } else {
          value = needle.substring ( 0, needle.indexOf ( '/'));
          needle = needle.substring ( needle.indexOf ( '/') + 1);
        }
        // Now, get key:
        if ( match.indexOf ( '/') == -1)
        {
          key = match;
          match = '';
        } else {
          key = match.substring ( 0, match.indexOf ( '/'));
          match = match.substring ( match.indexOf ( '/') + 1);
        }
        matches[key] = value;
      } else {
        if ( search != needle.substring ( 0, 1))
        {
          return false;
        }
        needle = needle.substring ( 1);
      }
    }

    if ( needle.length != 0)
    {
      return false;
    }

    return matches;
  }

  return this;
}) ();

function format_secs_to_string ( time)
{
  // If more than a day, add it
  result = '';
  if ( time >= 86400)
  {
    if ( Math.floor ( time / 86400) < 10)
    {
      result += '0';
    }
    result += Math.floor ( time / 86400) + ':';
    time = time % 86400;

    // Add hour
    if ( Math.floor ( time / 3600) < 10)
    {
      result += '0';
    }
    result += Math.floor ( time / 3600) + ':';
    time = time % 3600;
  } else {
    // If more than a hour, add it
    if ( time >= 3600)
    {
      if ( Math.floor ( time / 3600) < 10)
      {
        result += '0';
      }
      result += Math.floor ( time / 3600) + ':';
      time = time % 3600;
    }
  }

  // Add minute
  if ( Math.floor ( time / 60) < 10)
  {
    result += '0';
  }
  result += Math.floor ( time / 60) + ':';
  time = time % 60;

  // Add seconds
  if ( time < 10)
  {
    result += '0';
  }
  result += time;

  return result;
}

/**
 * VoIPDomain jQuery function extensions
 */
( function ( $)
{
  /**
   * jQuery form input field alerts
   */
  $.fn.alerts = function ( action, options)
  {
    var opts = $.extend ( {}, $.fn.alerts.defaults, options);
    return this.each ( function ()
    {
      var that = this;
      switch ( action)
      {
        case 'add':
          if ( typeof ( $(that).data ( 'select2')) === 'object')
          {
            $(that).data ( 'popover', $(that).next ( 'span.select2-container').popover ( { placement: opts.placement, content: opts.message, trigger: opts.trigger}).popover ( 'show'));
            $(that).parent ().find ( 'span.select2-selection').addClass ( opts.class);
          } else {
            $(that).data ( 'popover', $(that).popover ( { placement: opts.placement, content: opts.message, trigger: opts.trigger}).popover ( 'show'));
            $(that).addClass ( opts.class);
          }
          setTimeout ( function () { if ( $(that).data ( 'popover') != undefined) { $(that).data ( 'popover').popover ( 'destroy'); $(that).removeData ( 'popover'); } }, opts.timeout);
          break;
        case 'clear':
          if ( $(that).data ( 'popover') != undefined)
          {
            $(that).data ( 'popover').popover ( 'destroy');
            $(that).removeData ( 'popover');
          }
          if ( typeof ( $(that).data ( 'select2')) === 'object')
          {
            $(that).parent ().find ( 'span.select2-selection').removeClass ( opts.class);
          } else {
            $(that).removeClass ( opts.class);
          }
          break;
        case 'clearAll':
        case 'clearall':
          $(that).find ( 'input,select,textarea').each ( function () { $(this).alerts ( 'clear', opts); });
          break;
        case 'check':
          var tabs = false;
          var tab = '';
          if ( $(that).find ( 'ul.nav-tabs').length != 0)
          {
            tabs = true;
          }
          for ( var index in opts.errors)
          {
            if ( ! opts.errors.hasOwnProperty ( index) || index == 'result')
            {
              continue;
            }
            if ( $(that).find ( '[name="' + index + '"]').length != 0)
            {
              if ( tabs && tab == '')
              {
                tab = $(that).find ( '[name="' + index + '"]').closest ( 'div.tab-pane').attr ( 'id');
              }
              $(that).find ( '[name="' + index + '"]').alerts ( 'add', $.extend ( {}, opts, { message: opts.errors[index]}));
            }
          }
          if ( tabs && tab != '')
          {
            $(that).find ( 'a[href="#' + tab + '"]').tab ( 'show');
          }
          break;
        case 'form':
          $(that).on ( 'submit', function ( event)
          {
            event && event.preventDefault ();
            $(that).alerts ( 'clearAll');
            if ( typeof ( opts.form.button) == 'object')
            {
              $(opts.form.button).attr ( 'disabled', 'disabled');
              var l = Ladda.create ( $(opts.form.button)[0]);
              l.start ();
            }
            var data = VoIP.rest ( opts.form.URL, opts.form.method, $(that).serializeObject ());
            if ( typeof ( data.result) != 'object')
            {
              new PNotify ( { title: opts.form.title, text: opts.form.fail, type: 'error'});
              if ( typeof ( opts.form.onfail) == 'function')
              {
                opts.form.onfail ();
              }
            } else {
              if ( data.result.result === true)
              {
                new PNotify ( { title: opts.form.title, text: opts.form.success, type: 'success'});
                if ( typeof ( opts.form.onsuccess) == 'function')
                {
                  opts.form.onsuccess ();
                }
              } else {
                $(that).alerts ( 'check', { errors: data.result});
              }
            }
            if ( typeof ( opts.form.button) == 'object')
            {
              l.stop ();
              $(opts.form.button).removeAttr ( 'disabled');
            }
          });
          break;
      }
    });
  };

  // Plugin defaults
  $.fn.alerts.defaults =
  {
    message: '',
    placement: 'auto top',
    trigger: 'manual',
    timeout: 5000,
    class: 'alert-danger'
  };

  /**
   * jQuery serialize to object
   */
  $.fn.serializeObject = function ()
  {
    var o = {};
    var a = this.serializeArray ();
    $.each ( a, function ()
    {
      if ( o[this.name])
      {
        if ( ! o[this.name].push)
        {
          o[this.name] = [o[this.name]];
        }
        o[this.name].push ( this.value || '');
      } else {
        o[this.name] = this.value || '';
      }
    });
    return o;
  };

  /**
   * jQuery function to work with form data into URL hash
   */
  $.hashForm = function ( action, options)
  {
    switch ( action)
    {
      case 'check':
        if ( location.hash != '')
        {
          var data = JSON.parse ( '{"' + decodeURI ( location.hash.substring ( 1)).replace ( /"/g, '\\"').replace ( /&/g, '","').replace ( /=/g,'":"') + '"}');
          var filled = false;
          for ( var key in data)
          {
            if ( $('#' + key).length > 0)
            {
              $('#' + key).val ( decodeURIComponent ( data[key]));
              if ( typeof ( $.fn.select2) == 'function' && typeof ( $('#' + key).data ( 'select2')) == 'object')
              {
                $('#' + key).trigger ( 'change.select2');
              }
              filled = true;
            }
          }
          if ( filled == true && typeof options.onfill === 'function')
          {
            options.onfill ( data);
          }
        }
        if ( typeof options.onfinish === 'function')
        {
          options.onfinish ( data);
        }
        break;
      case 'set':
        if ( location.hash != $(options.form).serialize ())
        {
          history.replaceState ( null, null, ( location.hash ? location.href.substring ( 0, location.href.indexOf ( '#')) : location.href) + '#' + $(options.form).serialize ());
        }
        break;
    }
    return this;
  };
} ( jQuery));

$(document).ready ( function ()
{
  /**
   * Fix content when changing tab's (when element modified while hidden, normally get's bugged)
   */
  $('#content').on ( 'shown.bs.tab', function ( event)
  {
    $($(event.target).attr ( 'href')).find ( 'input,select,textarea').each ( function ()
    {
      if ( typeof ( $.fn.select2) == 'function' && typeof ( $(this).data ( 'select2')) == 'object')
      {
        $(this).trigger ( 'change.select2');
      }
      if ( typeof ( $.fn.slider) == 'function' && typeof ( $(this).data ( 'slider')) == 'object')
      {
        $(this).slider ( 'relayout');
      }
    });
  });

  /**
   * Dynamic page loading
   */
  var location = window.history.location || window.location;

  // Bind function on link click
  $(document).on ( 'click', 'a', function ( e)
  {
    // If it's hash link or empty, return false;
    if ( this.href.charAt ( location.href.length) == '#' || $(this).attr ( 'href') == '#' || $(this).attr ( 'href') == '' || this.href == location.href)
    {
      e && e.preventDefault ();
      return false;
    }

    // Return true if is an external link
    var href = location.protocol + '//' + location.hostname;
    if ( location.port != '')
    {
      href += ':' + location.port;
    }
    if ( this.href.substring ( 0, href.length) != href)
    {
      return true;
    }

    // Load requested link page
    VoIP.path.call ( this.href.substring ( href.length), true);

    return false;
  });

  $(window).on ( 'popstate', function ( e)
  {
    // Load requested link page
    VoIP.path.call ( location.href.replace ( /^.*\/\/[^\/]+/, ''), false);
  });

  /**
   * Process current page when ready
   */
  //VoIP.path.call ( location.href.replace ( /^.*\/\/[^\/]+/, ''), false);
});

/**
 * sprintf() JS implementation
 * Source from: http://locutus.io/php/strings/sprintf/
 */
function sprintf () {
  //  discuss at: http://locutus.io/php/sprintf/
  // original by: Ash Searle (http://hexmen.com/blog/)
  // improved by: Michael White (http://getsprink.com)
  // improved by: Jack
  // improved by: Kevin van Zonneveld (http://kvz.io)
  // improved by: Kevin van Zonneveld (http://kvz.io)
  // improved by: Kevin van Zonneveld (http://kvz.io)
  // improved by: Dj
  // improved by: Allidylls
  //    input by: Paulo Freitas
  //    input by: Brett Zamir (http://brett-zamir.me)
  // improved by: Rafał Kukawski (http://kukawski.pl)
  //   example 1: sprintf("%01.2f", 123.1)
  //   returns 1: '123.10'
  //   example 2: sprintf("[%10s]", 'monkey')
  //   returns 2: '[    monkey]'
  //   example 3: sprintf("[%'#10s]", 'monkey')
  //   returns 3: '[####monkey]'
  //   example 4: sprintf("%d", 123456789012345)
  //   returns 4: '123456789012345'
  //   example 5: sprintf('%-03s', 'E')
  //   returns 5: 'E00'
  //   example 6: sprintf('%+010d', 9)
  //   returns 6: '+000000009'
  //   example 7: sprintf('%+0\'@10d', 9)
  //   returns 7: '@@@@@@@@+9'
  //   example 8: sprintf('%.f', 3.14)
  //   returns 8: '3.140000'
  //   example 9: sprintf('%% %2$d', 1, 2)
  //   returns 9: '% 2'

  var regex = /%%|%(?:(\d+)\$)?((?:[-+#0 ]|'[\s\S])*)(\d+)?(?:\.(\d*))?([\s\S])/g
  var args = arguments
  var i = 0
  var format = args[i++]

  var _pad = function (str, len, chr, leftJustify) {
    if (!chr) {
      chr = ' '
    }
    var padding = (str.length >= len) ? '' : new Array(1 + len - str.length >>> 0).join(chr)
    return leftJustify ? str + padding : padding + str
  }

  var justify = function (value, prefix, leftJustify, minWidth, padChar) {
    var diff = minWidth - value.length
    if (diff > 0) {
      // when padding with zeros
      // on the left side
      // keep sign (+ or -) in front
      if (!leftJustify && padChar === '0') {
        value = [
          value.slice(0, prefix.length),
          _pad('', diff, '0', true),
          value.slice(prefix.length)
        ].join('')
      } else {
        value = _pad(value, minWidth, padChar, leftJustify)
      }
    }
    return value
  }

  var _formatBaseX = function (value, base, leftJustify, minWidth, precision, padChar) {
    // Note: casts negative numbers to positive ones
    var number = value >>> 0
    value = _pad(number.toString(base), precision || 0, '0', false)
    return justify(value, '', leftJustify, minWidth, padChar)
  }

  // _formatString()
  var _formatString = function (value, leftJustify, minWidth, precision, customPadChar) {
    if (precision !== null && precision !== undefined) {
      value = value.slice(0, precision)
    }
    return justify(value, '', leftJustify, minWidth, customPadChar)
  }

  // doFormat()
  var doFormat = function (substring, argIndex, modifiers, minWidth, precision, specifier) {
    var number, prefix, method, textTransform, value

    if (substring === '%%') {
      return '%'
    }

    // parse modifiers
    var padChar = ' ' // pad with spaces by default
    var leftJustify = false
    var positiveNumberPrefix = ''
    var j, l

    for (j = 0, l = modifiers.length; j < l; j++) {
      switch (modifiers.charAt(j)) {
        case ' ':
        case '0':
          padChar = modifiers.charAt(j)
          break
        case '+':
          positiveNumberPrefix = '+'
          break
        case '-':
          leftJustify = true
          break
        case "'":
          if (j + 1 < l) {
            padChar = modifiers.charAt(j + 1)
            j++
          }
          break
      }
    }

    if (!minWidth) {
      minWidth = 0
    } else {
      minWidth = +minWidth
    }

    if (!isFinite(minWidth)) {
      throw new Error('Width must be finite')
    }

    if (!precision) {
      precision = (specifier === 'd') ? 0 : 'fFeE'.indexOf(specifier) > -1 ? 6 : undefined
    } else {
      precision = +precision
    }

    if (argIndex && +argIndex === 0) {
      throw new Error('Argument number must be greater than zero')
    }

    if (argIndex && +argIndex >= args.length) {
      throw new Error('Too few arguments')
    }

    value = argIndex ? args[+argIndex] : args[i++]

    switch (specifier) {
      case '%':
        return '%'
      case 's':
        return _formatString(value + '', leftJustify, minWidth, precision, padChar)
      case 'c':
        return _formatString(String.fromCharCode(+value), leftJustify, minWidth, precision, padChar)
      case 'b':
        return _formatBaseX(value, 2, leftJustify, minWidth, precision, padChar)
      case 'o':
        return _formatBaseX(value, 8, leftJustify, minWidth, precision, padChar)
      case 'x':
        return _formatBaseX(value, 16, leftJustify, minWidth, precision, padChar)
      case 'X':
        return _formatBaseX(value, 16, leftJustify, minWidth, precision, padChar)
          .toUpperCase()
      case 'u':
        return _formatBaseX(value, 10, leftJustify, minWidth, precision, padChar)
      case 'i':
      case 'd':
        number = +value || 0
        // Plain Math.round doesn't just truncate
        number = Math.round(number - number % 1)
        prefix = number < 0 ? '-' : positiveNumberPrefix
        value = prefix + _pad(String(Math.abs(number)), precision, '0', false)

        if (leftJustify && padChar === '0') {
          // can't right-pad 0s on integers
          padChar = ' '
        }
        return justify(value, prefix, leftJustify, minWidth, padChar)
      case 'e':
      case 'E':
      case 'f': // @todo: Should handle locales (as per setlocale)
      case 'F':
      case 'g':
      case 'G':
        number = +value
        prefix = number < 0 ? '-' : positiveNumberPrefix
        method = ['toExponential', 'toFixed', 'toPrecision']['efg'.indexOf(specifier.toLowerCase())]
        textTransform = ['toString', 'toUpperCase']['eEfFgG'.indexOf(specifier) % 2]
        value = prefix + Math.abs(number)[method](precision)
        return justify(value, prefix, leftJustify, minWidth, padChar)[textTransform]()
      default:
        // unknown specifier, consume that char and return empty
        return ''
    }
  }

  try {
    return format.replace(regex, doFormat)
  } catch (err) {
    return false
  }
}
