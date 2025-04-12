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
 * VoIP Domain main JavaScript framework control script. This script implements
 * all JavaScript code needed to interface to work.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Core
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * VoIP Domain object
 */
var VoIP = ( function ()
{
  // Private variables:
  var modules = {};
  var objects = {};
  var paths = {};
  var lastpath = '';
  var lastpage = '';
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
  var nonce = '';
  var token = '';
  var defaultcurrency = '';
  var i18ndata = {};

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
    if ( 'nonce' in variables)
    {
      nonce = variables.nonce;
    }
    if ( 'token' in variables)
    {
      token = variables.token;
    }
    if ( 'defaultcurrency' in variables)
    {
      defaultcurrency = variables.defaultcurrency;
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
  this.getTitle = function ()
  {
    return title;
  }
  this.getToken = function ()
  {
    return token;
  }
  this.getNonce = function ()
  {
    return nonce;
  }
  this.getCurrency = function ()
  {
    return defaultcurrency;
  }

  /**
   * Interface specific functions
   */
  this.interface = {
    addObject: function ( object)
               {
                 objects[object['object']] = { 'icon': object['icon'], 'label': object['label'], 'text': object['text'], 'type': object['type'], 'path': object['path']};
               },
    objIcon: function ( object)
             {
               return typeof ( objects[object]) == 'object' ? objects[object].icon : 'exclamation-circle';
             },
    objLabel: function ( object)
              {
                return typeof ( objects[object]) == 'object' ? objects[object].label : 'default';
              },
    objText: function ( object)
             {
               return typeof ( objects[object]) == 'object' ? objects[object].text : '';
             },
    objType: function ( object)
             {
               return typeof ( objects[object]) == 'object' ? objects[object].type : '';
             },
    objPath: function ( object)
             {
               return typeof ( objects[object]) == 'object' ? objects[object].path : '';
             },
    objTextSingular: function ( object)
             {
               return typeof ( objects[object]) == 'object' ? objects[object].text.singular : '';
             },
    objTextPlural: function ( object)
             {
               return typeof ( objects[object]) == 'object' ? objects[object].text.plural : '';
             },
    objList: function ( filter, removeFilter = false)
             {
               var result = new Array ();
               for ( var object in objects)
               {
                 if ( arguments.length == 0 || ( arguments.length >= 1 && object.search ( filter) != -1))
                 {
                   if ( removeFilter)
                   {
                     result.push ( { id: object.substr ( filter.length), text: objects[object].text.singular});
                   } else {
                     result.push ( { id: object, text: objects[object].text.singular});
                   }
                 }
               }
               return result;
             }
  };

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
    fire: function ( module, parameters, onfinish)
          {
            console.log ( 'Firing module ' + module + ' with parameters: ' + JSON.stringify ( parameters));

            // Export parameters
            VoIP.parameters = parameters;

            // If module is a function, just execute JavaScript code
            if ( modules[module].type === 'function' && 'inlinejs' in modules[module])
            {
              $('<script type="text/javascript" nonce="' + VoIP.getNonce () + '">').text ( '//# sourceURL=/modules/' + module + '.js\n\n' + modules[module].inlinejs).appendTo ( 'body');

              // If onfinish function provided, execute it:
              if ( typeof ( onfinish) === 'function')
              {
                onfinish ( module, parameters);
              }
              return;
            }

            // If not same page, rebuild content
            if ( ( lastpage.indexOf ( '#') != -1 ? lastpage.substring ( 0, lastpage.indexOf ( '#')) : lastpage) != ( location.href.indexOf ( '#') != -1 ? location.href.substring ( 0, location.href.indexOf ( '#')) : location.href))
            {
              // Reset menu
              $('ul.sidebar-menu').find ( '.menu-open').removeClass ( 'menu-open').find ( 'ul').css ( 'display', '');
              $('ul.sidebar-menu').find ( 'li.active').removeClass ( 'active');

              // Make current menu entry as active
              var element = $('ul.sidebar-menu a').filter ( function ()
              {
                return this.href == ( location.href.indexOf ( '#') != -1 ? location.href.substring ( 0, location.href.indexOf ( '#')) : location.href) && $(this).attr ( 'href');
              }).parent ( 'li').addClass ( 'active');
              if ( $(element).length == 1)
              {
                while ( ! $(element).hasClass ( 'sidebar-menu'))
                {
                  element = $(element).parent ();
                  if ( $(element).is ( 'ul') && $(element).hasClass ( 'treeview-menu'))
                  {
                    $(element).css ( 'display', 'block').parent ().addClass ( 'menu-open');
                  }
                }
              }

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
                var breadcrumb = '<li' + ( modules[module].breadcrumb.length == 0 ? ' class="active"' : '><a href="/"') + '><i class="fa fa-home"></i>' + ( modules[module].breadcrumb.length == 0 ? '' : '</a>') + '</li>';
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

              // If there's custom JavaScript code, add it
              if ( 'inlinejs' in modules[module])
              {
                $('<script type="text/javascript" nonce="' + VoIP.getNonce () + '">').text ( '//# sourceURL=/modules/' + module + '.js\n\n' + modules[module].inlinejs).appendTo ( 'body');
              }

              // Active any tooltip if exists
              setTimeout ( function () { $('#content [data-toggle="tooltip"]').tooltip ( { container: 'body'}); }, 0);
            }

            // If URL hash exist, process it:
            hasheventfired = false;
            if ( location.hash)
            {
              // Decode URL hash
              try
              {
                hashdata = JSON.parse ( atob ( location.hash.substr ( 1)));
              } catch ( e) {
                hashdata = {};
              }

              // Check if tab is specified to be shown
              if ( hashdata.Tab && $('.nav-tabs a[href="' + hashdata.Tab + '"]').length != 0)
              {
                $('.nav-tabs a[href="' + hashdata.Tab + '"]').tab ( 'show');
                delete ( hashdata.Tab);
              }

              // If module has hashevent, execute it
              if ( hashdata && 'hashevent' in modules[module] && $._data ( $('#' + modules[module].hashevent.id)[0], 'events') && modules[module].hashevent.event in $._data ( $('#' + modules[module].hashevent.id)[0], 'events') && $._data ( $('#' + modules[module].hashevent.id)[0], 'events')[modules[module].hashevent.event].length > 0)
              {
                $('#' + modules[module].hashevent.id).trigger ( modules[module].hashevent.event, hashdata);
                hasheventfired = true;
              }
            }

            // Execute page start event if hash event wasn't fired:
            if ( ! hasheventfired && 'startevent' in modules[module] && $._data ( $('#' + modules[module].startevent.id)[0], 'events') && modules[module].startevent.event in $._data ( $('#' + modules[module].startevent.id)[0], 'events') && $._data ( $('#' + modules[module].startevent.id)[0], 'events')[modules[module].startevent.event].length > 0)
            {
              $('#' + modules[module].startevent.id).trigger ( modules[module].startevent.event, modules[module].startevent.data);
            }

//            // Refresh any time with timeago if exists
//            $('#content time.timeago').timeago ();

            // If onfinish function provided, execute it:
            if ( typeof ( onfinish) === 'function')
            {
              onfinish ( module, parameters);
            }

            // Set lastpage to current page
            lastpage = location.href;
          },
    load: function ( path, callonload, pushonload, onfinish)
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

            // If URL has parameters, remove it to request
            var url = ( path.indexOf ( '?') != -1 ? path.substring ( 0, path.indexOf ( '?')) : path);

            // If URL has hashtag, remove it to request
            url = ( url.indexOf ( '#') != -1 ? url.substring ( 0, url.indexOf ( '#')) : url);

            $.ajax (
            {
              url: url,
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
                           $('<style type="text/css" nonce="' + VoIP.getNonce () + '">').text ( data.inlinecss).appendTo ( 'head');
                         }

                         // Load data using jquery.loader
                         $.loader (
                         {
                           js: data.js,
                           css: data.css,
                           nonce: VoIP.getNonce (),
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
                                         VoIP.path.call ( path, pushonload, onfinish);
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
   * Internationalization functions
   */
  this.i18n = {
    add: function ( string1, string2)
         {
           i18ndata[string1] = string2;
         },
    __: function ( string)
        {
          if ( i18ndata.hasOwnProperty ( string))
          {
            return i18ndata[string];
          }
          return string;
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
    call: function ( path, push, onfinish)
          {
            push = ( typeof ( push) === 'undefined' ? true : push);
            searchpath = ( path.indexOf ( '#') != -1 ? path.substring ( 0, path.indexOf ( '#')) : path);
            searchpath = ( searchpath.indexOf ( '?') != -1 ? searchpath.substring ( 0, searchpath.indexOf ( '?')) : searchpath);

            // If request is logout, destroy session and redirect
            if ( path == '/logout')
            {
              VoIP.rest ( '/session', 'DELETE').always ( function ()
              {
                location.href = '/';
              });
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
                module.fire ( paths[key], match, onfinish);
                return;
              }
            }

            // Ok, we don't have the path and module. Try to fetch it from server:
            module.load ( path, true, push, onfinish);
          }
  };

  /**
   * REST API search dataTables/select2 format wrapper
   */
  this.APIsearch = function ( parameters)
  {
    /**
     * Default options
     */
    var options = {
      path: '',
      route: 'GET',
      fields: '',
      filters: {},
      formatID: 'ID',
      formatText: '',
      indexedFilter: true,
      localFormatText: function ( e)
                       {
                         return e;
                       },
      filter: function ( results, fields)
              {
                var result = new Array ();
                for ( var x = 0; x < results.length; x++)
                {
                  var entry = new Array ();
                  for ( var y = 0; y < fields.length; y++)
                  {
                    if ( fields[y] == 'NULL')
                    {
                      if ( options.indexedFilter)
                      {
                        entry[fields[y]] = '';
                      } else {
                        entry.push ( '');
                      }
                    } else {
                      if ( options.indexedFilter)
                      {
                        entry[fields[y]] = results[x][fields[y]];
                      } else {
                        entry.push ( results[x][fields[y]]);
                      }
                    }
                  }
                  result.push ( entry);
                }
                return result;
              },
      data: {}
    };

    /**
     * Override options with client options
     */
    if ( typeof ( parameters.path) == 'string')
    {
      options.path = parameters.path;
    }
    if ( typeof ( parameters.route) == 'string')
    {
      options.route = parameters.route;
    }
    if ( typeof ( parameters.fields) == 'string')
    {
      options.fields = parameters.fields;
    }
    if ( typeof ( parameters.localFormatText) == 'function')
    {
      options.localFormatText = parameters.localFormatText;
    }
    if ( typeof ( parameters.formatID) == 'string')
    {
      options.formatID = parameters.formatID;
    }
    if ( typeof ( parameters.formatText) == 'string')
    {
      options.formatText = parameters.formatText;
    }
    if ( typeof ( parameters.data) == 'object')
    {
      options.data = parameters.data;
    }
    if ( typeof ( parameters.indexedFilter) == 'boolean')
    {
      options.indexedFilter = parameters.indexedFilter;
    }
    if ( typeof ( parameters.filter) == 'function')
    {
      options.filter = parameters.filter;
    }
    if ( typeof ( parameters.filters) == 'object')
    {
      options.filters = parameters.filters;
    }

    /**
     * Add fields to request
     */
    options.data.Fields = options.fields;

    /**
     * Request search from API
     */
    var data = rest ( options.path, options.route, options.data, false);

    /**
     * Process the result
     */
    if ( typeof ( data.result) == 'object' && data.API.status == 'ok')
    {
      if ( data.result.length == 0)
      {
        return data.result;
      }
      if ( options.formatText != '')
      {
        var fields = Object.keys ( data.result[0]);
        var result = new Array ();
        for ( var x = 0; x < data.result.length; x++)
        {
          var text = options.formatText;
          for ( var y = 0; y < fields.length; y++)
          {
            text = text.replace ( '%' + fields[y] + '%', data.result[x][fields[y]]);
          }
          result.push ( { id: data.result[x][options.formatID], text: options.localFormatText ( text)});
        }
        return result;
      } else {
        for ( var field in options.filters)
        {
          if ( ! options.filters.hasOwnProperty ( field))
          {
            continue;
          }
          if ( typeof ( options.filters[field]) == 'function')
          {
            options.filters[field] ( data.result);
          } else {
            switch ( options.filters[field])
            {
              case 'datetime':
                for ( var x = 0; x < data.result.length; x++)
                {
                  data.result[x][field] = moment ( data.result[x][field]).isValid () ? moment ( data.result[x][field]).format ( 'L LTS') : '';
                }
                break;
              case 'date':
                for ( var x = 0; x < data.result.length; x++)
                {
                  data.result[x][field] = moment ( data.result[x][field]).isValid () ? moment ( data.result[x][field]).format ( 'L') : '';
                }
                break;
              case 'time':
                for ( var x = 0; x < data.result.length; x++)
                {
                  data.result[x][field] = moment ( data.result[x][field]).isValid () ? moment ( data.result[x][field]).format ( 'LTS') : '';
                }
                break;
            }
          }
        }
        return options.filter ( data.result, options.fields.split ( ','));
      }
    } else {
      console.log ( 'VoIP API search dataTables/select2 wrapper request error. Status: ' + data.API.statusCode + ' ' + data.API.statusText);
      return new Array ();
    }
  }

  /**
   * API search dataTables/select2 format wrapper and updater
   */
  this.dataTablesUpdate = function ( parameters, dt)
  {
    /**
     * Set DataTables processing mode
     */
    dt.processing ( true);

    /**
     * Default options
     */
    var options = {
      path: '',
      route: 'GET',
      fields: '',
      filters: {},
      formatID: 'ID',
      formatText: '',
      indexedFilter: true,
      localFormatText: function ( e)
                       {
                         return e;
                       },
      filter: function ( results, fields)
              {
                var result = new Array ();
                for ( var x = 0; x < results.length; x++)
                {
                  var entry = new Array ();
                  for ( var y = 0; y < fields.length; y++)
                  {
                    if ( fields[y] == 'NULL')
                    {
                      if ( options.indexedFilter)
                      {
                        entry[fields[y]] = '';
                      } else {
                        entry.push ( '');
                      }
                    } else {
                      if ( options.indexedFilter)
                      {
                        entry[fields[y]] = results[x][fields[y]];
                      } else {
                        entry.push ( results[x][fields[y]]);
                      }
                    }
                  }
                  result.push ( entry);
                }
                return result;
              },
      data: {}
    };

    /**
     * Override options with client options
     */
    if ( typeof ( parameters.path) == 'string')
    {
      options.path = parameters.path;
    }
    if ( typeof ( parameters.route) == 'string')
    {
      options.route = parameters.route;
    }
    if ( typeof ( parameters.fields) == 'string')
    {
      options.fields = parameters.fields;
    }
    if ( typeof ( parameters.localFormatText) == 'function')
    {
      options.localFormatText = parameters.localFormatText;
    }
    if ( typeof ( parameters.formatID) == 'string')
    {
      options.formatID = parameters.formatID;
    }
    if ( typeof ( parameters.formatText) == 'string')
    {
      options.formatText = parameters.formatText;
    }
    if ( typeof ( parameters.data) == 'object')
    {
      options.data = parameters.data;
    }
    if ( typeof ( parameters.indexedFilter) == 'boolean')
    {
      options.indexedFilter = parameters.indexedFilter;
    }
    if ( typeof ( parameters.filter) == 'function')
    {
      options.filter = parameters.filter;
    }
    if ( typeof ( parameters.filters) == 'object')
    {
      options.filters = parameters.filters;
    }

    /**
     * Add fields to request
     */
    options.data.Fields = options.fields;

    /**
     * Request search from API
     */
    VoIP.rest ( options.path, options.route, options.data).done ( function ( data, textStatus, jqXHR)
    {
      if ( data.length == 0)
      {
        dt.clear ();
        dt.rows.add ( data);
        dt.draw ();
        dt.responsive.recalc ();
        dt.processing ( false);
        return;
      }
      if ( options.formatText != '')
      {
        var fields = Object.keys ( data[0]);
        var result = new Array ();
        for ( var x = 0; x < data.length; x++)
        {
          var text = options.formatText;
          for ( var y = 0; y < fields.length; y++)
          {
            text = text.replace ( '%' + fields[y] + '%', data[x][fields[y]]);
          }
          result.push ( { id: data[x][options.formatID], text: options.localFormatText ( text)});
        }
        dt.clear ();
        dt.rows.add ( result);
        dt.draw ();
        dt.responsive.recalc ();
        dt.processing ( false);
        return;
      } else {
        for ( var field in options.filters)
        {
          if ( ! options.filters.hasOwnProperty ( field))
          {
            continue;
          }
          if ( typeof ( options.filters[field]) == 'function')
          {
            options.filters[field] ( data);
          } else {
            switch ( options.filters[field])
            {
              case 'datetime':
                for ( var x = 0; x < data.length; x++)
                {
                  data[x][field] = moment ( data[x][field]).isValid () ? moment ( data[x][field]).format ( 'L LTS') : '';
                }
                break;
              case 'date':
                for ( var x = 0; x < data.length; x++)
                {
                  data[x][field] = moment ( data[x][field]).isValid () ? moment ( data[x][field]).format ( 'L') : '';
                }
                break;
              case 'time':
                for ( var x = 0; x < data.length; x++)
                {
                  data[x][field] = moment ( data[x][field]).isValid () ? moment ( data[x][field]).format ( 'LTS') : '';
                }
                break;
            }
          }
        }
        dt.clear ();
        dt.rows.add ( options.filter ( data, options.fields.split ( ',')));
        dt.draw ();
        dt.responsive.recalc ();
        dt.processing ( false);
        return;
      }
    }).fail ( function ( jqXHR, textStatus, errorThrown)
    {
      console.log ( 'VoIP API search dataTables/select2 wrapper request error. Status: ' + jqXHR.status + ' ' + textStatus);
      dt.clear ();
      dt.draw ();
      dt.responsive.recalc ();
      dt.processing ( false);
    });
  }

  /**
   * REST API connections
   */
  this.rest = function ( path, route, data = undefined, async = true)
  {
    if ( path.substring ( 0, 1) != '/')
    {
      path = '/' + path;
    }
    var result = null;
    var sentHeaders = null;
    var myheaders =
    {
      'X-INFramework': 'api',
      'X-HTTP-Method-Override': route,
      'Accept': 'application/json'
    }
    if ( token)
    {
      myheaders['X-VD-Token'] = token;
    }

    /**
     * Non-asynchronous call (block browser processing)
     */
    if ( async === false)
    {
      $.ajax (
      {
        type: 'POST',
        url: apiurl + path,
        data: JSON.stringify ( data),
        async: false,
        cache: false,
        headers: myheaders,
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',
        beforeSend: function ( jqXHR, settings)
                    {
                      sentHeaders = settings.headers;
                    },
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
                 if ( jqXHR.statusCode ().status >= 500 && jqXHR.statusCode ().status <= 599 && typeof document.dump_vd_debug === 'function')
                 {
                   document.dump_vd_debug ( { endpoint: path, route: route, data: data, sentHeaders: sentHeaders, result: result});
                 }
               }
      });

      return result;
    }

    /**
     * Asynchronous call
     */
    return $.ajax (
    {
      type: 'POST',
      url: apiurl + path,
      data: JSON.stringify ( data),
      async: true,
      cache: false,
      headers: myheaders,
      contentType: 'application/json; charset=utf-8',
      dataType: 'json',
      beforeSend: function ( jqXHR, settings)
                  {
                    sentHeaders = settings.headers;
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
               if ( jqXHR.statusCode ().status >= 500 && jqXHR.statusCode ().status <= 599 && typeof document.dump_vd_debug === 'function')
               {
                 document.dump_vd_debug ( { endpoint: path, route: route, data: data, sentHeaders: sentHeaders, result: { result: jsonresult, API: { status: 'error', content: jqXHR.responseText, statusCode: jqXHR.statusCode ().status, statusText: jqXHR.statusCode ().statusText, headers: jqXHR.getAllResponseHeaders ()}}});
               }
             }
    });
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
          var errors = opts.errors;
          var flatted = false;
          while ( ! flatted)
          {
            flatted = true;
            for ( let index in errors)
            {
              if ( typeof ( errors[index]) === 'object')
              {
                for ( let subindex in errors[index])
                {
                  errors[index + '_' + subindex] = errors[index][subindex];
                }
                delete errors[index];
                flatted = false;
              }
            }
          }
          for ( var index in errors)
          {
            if ( ! errors.hasOwnProperty ( index))
            {
              continue;
            }
            if ( $(that).find ( '[name="' + index + '"]').length != 0)
            {
              if ( tabs && tab == '')
              {
                tab = $(that).find ( '[name="' + index + '"]').closest ( 'div.tab-pane').attr ( 'id');
                if ( $('#' + tab).parent ().closest ( 'div.tab-pane').length)
                {
                  $(that).find ( 'a[href="#' + $('#' + tab).parent ().closest ( 'div.tab-pane').attr ( 'id') + '"]').tab ( 'show');
                }
                $(that).find ( 'a[href="#' + tab + '"]').tab ( 'show');
              }
              $(that).find ( '[name="' + index + '"]').alerts ( 'add', $.extend ( {}, opts, { message: errors[index]}));
            }
          }
          break;
        case 'form':
          // Enforce button to trigger form submit:
          if ( typeof ( opts.form.button) == 'object')
          {
            $(opts.form.button).on ( 'click', function ( event)
            {
              $(that).trigger ( 'submit', { type: 'default'});
              event && event.preventDefault ();
            });
            $(opts.form.button).parent ().find ( '.dropdown-item').each ( function ()
            {
              $(this).on ( 'click', function ( event)
              {
                var type = 'unknown';
                if ( $(this).hasClass ( 'add-new'))
                {
                  type = 'new';
                }
                if ( $(this).hasClass ( 'add-duplicate'))
                {
                  type = 'duplicate';
                }
                $(that).trigger ( 'submit', { type: type});
                event && event.preventDefault ();
              });
            });
          }
          // Set form focus if provided:
          if ( typeof ( opts.form.focus) == 'object')
          {
            $(opts.form.focus).focus ();
          }
          // Form submit action:
          $(that).on ( 'submit', function ( event, eventopts)
          {
            event && event.preventDefault ();
            $(that).find ( '.open').removeClass ( 'open');
            $(that).alerts ( 'clearAll');
            if ( typeof ( opts.form.onsubmit) == 'function')
            {
              if ( ! opts.form.onsubmit ( event, opts))
              {
                return false;
              }
            }

            // If button exist, disable it:
            if ( typeof ( opts.form.button) == 'object')
            {
              $(opts.form.button).attr ( 'disabled', 'disabled');
              // Also disable dropdown, if exist:
              if ( $(opts.form.button).parent ().find ( '.dropdown-toggle').length != 0)
              {
                $(opts.form.button).parent ().find ( '.dropdown-toggle').attr ( 'disabled', 'disabled');
              }
              // Start ladda spin if configured:
              if ( $(opts.form.button).hasClass ( 'ladda-button'))
              {
                var l = Ladda.create ( $(opts.form.button)[0]);
                l.start ();
              }
            }

            // Trigger formFilter events:
            $(that).data ( 'formData', $(that).serializeObject ());
            $(that).trigger ( 'formFilter');
            var formData = $(that).data ( 'formData');

            // Filter fields:
            for ( var field in opts.form.filters)
            {
              if ( ! opts.form.filters.hasOwnProperty ( field) || ! formData[field])
              {
                continue;
              }
              if ( typeof ( opts.form.filters[field]) == 'function')
              {
                formData[field] = opts.form.filters[field] ( formData[field]);
              } else {
                switch ( opts.form.filters[field])
                {
                  case 'datetime':
                    formData[field] = moment ( formData[field], 'L LTS').isValid () ? moment ( formData[field], 'L LTS').utc ().format () : '';
                    break;
                  case 'date':
                    formData[field] = moment ( formData[field], 'L').isValid () ? moment ( formData[field], 'L').utc ().format ( 'L') : '';
                    break;
                  case 'time':
                    formData[field] = moment ( formData[field], 'LTS').isValid () ? moment ( formData[field], 'LTS').utc ().format ( 'LTS') : '';
                    break;
                }
              }
            }

            // Send form data and validate it:
            if ( typeof ( opts.form.onvalidate) != 'function' || ( typeof ( opts.form.onvalidate) == 'function' && opts.form.onvalidate ( formData)))
            {
              VoIP.rest ( opts.form.URL, opts.form.method, formData).done ( function ( data, textStatus, jqXHR)
              {
                if ( jqXHR.status == 200 || jqXHR.status == 201 || jqXHR.status == 204)
                {
                  if ( opts.form.success)
                  {
                    new PNotify ( { title: opts.form.title, text: opts.form.success, type: 'success'});
                  }
                  var type = eventopts && eventopts.type ? eventopts.type : 'default';
                  switch ( eventopts.type)
                  {
                    case 'new':
                      $(that).trigger ( 'reset');
                      if ( typeof ( opts.form.focus) == 'object')
                      {
                        $(opts.form.focus).focus ();
                      }
                      break;
                    case 'duplicate':
                      if ( typeof ( opts.form.focus) == 'object')
                      {
                        $(opts.form.focus).focus ();
                      }
                      break;
                    default:
                      if ( typeof ( opts.form.onsuccess) == 'function')
                      {
                        opts.form.onsuccess ( data);
                      }
                      break;
                  }
                } else {
                  $(that).alerts ( 'check', { errors: data});
                }
              }).fail ( function ( jqXHR, textStatus, errorThrown)
              {
                if ( opts.form.fail)
                {
                  new PNotify ( { title: opts.form.title, text: opts.form.fail, type: 'error'});
                }
                if ( typeof ( opts.form.onfail) == 'function')
                {
                  opts.form.onfail ( data);
                }
                try
                {
                  var jsonresult = JSON.parse ( jqXHR.responseText);
                } catch ( e) {
                  var jsonresult = null;
                }
                if ( jsonresult)
                {
                  $(that).alerts ( 'check', { errors: jsonresult});
                }
              }).always ( function ( data, textStatus, jqXHR)
              {
                if ( typeof ( opts.form.button) == 'object')
                {
                  $(opts.form.button).removeAttr ( 'disabled');
                  // Also enable dropdown, if exist:
                  if ( $(opts.form.button).parent ().find ( '.dropdown-toggle').length != 0)
                  {
                    $(opts.form.button).parent ().find ( '.dropdown-toggle').removeAttr ( 'disabled');
                  }
                  // Stop ladda spin if configured:
                  if ( $(opts.form.button).hasClass ( 'ladda-button'))
                  {
                    l.stop ();
                  }
                }
              });
            }
            if ( typeof ( opts.form.button) == 'object')
            {
              $(opts.form.button).removeAttr ( 'disabled');
              // Also enable dropdown, if exist:
              if ( $(opts.form.button).parent ().find ( '.dropdown-toggle').length != 0)
              {
                $(opts.form.button).parent ().find ( '.dropdown-toggle').removeAttr ( 'disabled');
              }
              // Stop ladda spin if configured:
              if ( $(opts.form.button).hasClass ( 'ladda-button'))
              {
                l.stop ();
              }
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
    class: 'alert-danger',
    formFilter: '',
    fail: '',
    success: ''
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
   * jQuery function to retrieve current page parameters
   */
  $.urlParam = function ( parameter)
  {
    var results = new RegExp ( '[\?&]' + parameter + '=([^&#]*)').exec ( location.href);
    if ( results == null)
    {
       return null;
    }
    return decodeURI ( results[1]) || 0;
  }

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
          try
          {
            var data = JSON.parse ( atob ( location.hash.substr ( 1)));
          } catch ( e) {
            var data = {};
          }
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
        hashdata = btoa ( JSON.stringify ( options.data));
        if ( location.hash != '#' + hashdata)
        {
          history.pushState ( null, null, ( location.hash ? location.href.substring ( 0, location.href.indexOf ( '#')) : location.href) + '#' + hashdata);
        }
        break;
      case 'destroy':
        if ( location.hash)
        {
          history.pushState ( null, null, ( location.hash ? location.href.substring ( 0, location.href.indexOf ( '#')) : location.href));
        }
        break;
    }
    return this;
  };
} ( jQuery));

/**
 * VoIP Domain document ready procedures
 */
$(document).ready ( function ()
{
  /**
   * Active menu tree
   */
  $('ul.sidebar-menu').tree ();

  /**
   * Create live tab click action
   */
  $('#content').on ( 'click', '.nav-tabs a', function ( e)
  {
    e.preventDefault ();
    $(this).tab ( 'show');
  });

  /**
   * On menu click, remove active class from other elements and active clicked entry
   */
  $('section.sidebar').on ( 'click', 'ul.sidebar-menu a:not([href=""])', function ( e)
  {
    $('section.sidebar ul.sidebar-menu').find ( '.active').each ( function ()
    {
      $(this).removeClass ( 'active');
    });
    $(this).parent ( 'li').addClass ( 'active');
  });

  /**
   * On label click, check if field has select2 enabled, if has, open it
   */
  $(document).on ( 'click', 'label', function ( e)
  {
    if ( typeof ( $.fn.select2) == 'function' && $('#' + $(this).attr ( 'for')).not ( '.noauto').hasClass ( 'select2-hidden-accessible'))
    {
      $('#' + $(this).attr ( 'for')).focus ();
      e && e.preventDefault ();
    }
    if ( typeof ( $.fn.bootstrapToggle) == 'function' && $('#' + $(this).attr ( 'for')).not ( '.noauto').is ( ':checkbox'))
    {
      $('#' + $(this).attr ( 'for')).bootstrapToggle ( 'toggle');
      e && e.preventDefault ();
    }
  });

  /**
   * Bootstrap fixes
   */

  // Remove bootstrap modal enforce focus function, because it conflicts with select2 plugin:
  $.fn.modal.Constructor.prototype.enforceFocus = function () {};

  // Disable click on disabled bootstrap tabs:
  $(document).on ( 'show.bs.tab', function ( e)
  {
    if ( $(e.target).parent ().hasClass ( 'disabled'))
    {
      e && e.preventDefault ();
      return false;
    }
  });

  /**
   * PNotify customization
   */
  if ( typeof PNotify == 'function')
  {
    PNotify.prototype.options.styling = 'bootstrap3';
    PNotify.prototype.options.animate_speed = 'slow';
  }

  /**
   * jQuery Select2 customization
   */
  if ( typeof $.fn.select2 == 'function')
  {
    $.fn.select2.defaults.set ( 'theme', 'bootstrap');
    $.fn.select2.defaults.set ( 'width', '100%');
  }

  /**
   * jQuery Select2 fixes
   */

  // Select2 focus menu open fix (source: https://stackoverflow.com/questions/20989458/select2-open-dropdown-on-focus)
  $(document).on ( 'focus', '.select2-selection.select2-selection--single', function ( e)
  {
    $(this).closest ( '.select2-container').siblings ( 'select:enabled').select2 ( 'open');
  }).on ( 'select2:closing', function ( e)
  {
    $(e.target).data ( 'select2').$selection.one ( 'focus focusin', function ( e)
    {
      e.stopPropagation ();
    });
  });

  // Add automatic refresh on select2 fields when a tab is displayed (avoid positioning and size bug):
  $('#content a.nav-tablink').on ( 'shown.bs.tab', function ( e)
  {
    $($(this).attr ( 'href')).find ( 'select.select2-hidden-accessible').each ( function ()
    {
      $(this).trigger ( 'change.select2');
    });
  });
  $(document).on ( 'shown.bs.modal', function ( e)
  {
    $(this).find ( 'div[role="tabpanel"].active').find ( 'select.select2-hidden-accessible').each ( function ()
    {
      $(this).trigger ( 'change.select2');
    });
  });

  /**
   * Fix content when changing tab's (when element modified while hidden, normally get's bugged)
   */
  $('#content').on ( 'shown.bs.tab', function ( e)
  {
    $($(e.target).attr ( 'href')).find ( 'input,select,textarea').each ( function ()
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
   * System fast search
   */
  $('#fastsearch').on ( 'submit', function ( e)
  {
    e && e.preventDefault ();
    VoIP.path.call ( '/fastsearch/' + encodeURIComponent ( $('#fastsearch input[name="q"]').val ()), true);
  });

  // Bind function on link click
  $(document).on ( 'click', 'a', function ( e)
  {
    // If it's hash link or empty, return false;
    if ( this.href.charAt ( location.href.length) == '#' || $(this).attr ( 'href') == '#' || $(this).attr ( 'href') == '' || this.href == location.href || $(this).hasClass ( 'player'))
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
    VoIP.path.call ( this.href.substring ( href.length), $(this).data ( 'nohistory') ? false : true);

    return false;
  });

  /**
   * Dynamic page loading
   */
  var location = window.history.location || window.location;

  $(window).on ( 'popstate', function ( e)
  {
    // Load requested link page
    VoIP.path.call ( location.href.replace ( /^.*\/\/[^\/]+/, ''), false);
  });
});

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

/**
 * number_format() JS implementation
 * Source from: http://locutus.io/php/strings/number_format/
 */
function number_format ( number, decimals, decPoint, thousandsSep)
{
  // eslint-disable-line camelcase
  //  discuss at: http://locutus.io/php/number_format/
  // original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
  // improved by: Kevin van Zonneveld (http://kvz.io)
  // improved by: davook
  // improved by: Brett Zamir (http://brett-zamir.me)
  // improved by: Brett Zamir (http://brett-zamir.me)
  // improved by: Theriault (https://github.com/Theriault)
  // improved by: Kevin van Zonneveld (http://kvz.io)
  // bugfixed by: Michael White (http://getsprink.com)
  // bugfixed by: Benjamin Lupton
  // bugfixed by: Allan Jensen (http://www.winternet.no)
  // bugfixed by: Howard Yeend
  // bugfixed by: Diogo Resende
  // bugfixed by: Rival
  // bugfixed by: Brett Zamir (http://brett-zamir.me)
  //  revised by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
  //  revised by: Luke Smith (http://lucassmith.name)
  //    input by: Kheang Hok Chin (http://www.distantia.ca/)
  //    input by: Jay Klehr
  //    input by: Amir Habibi (http://www.residence-mixte.com/)
  //    input by: Amirouche
  //   example 1: number_format(1234.56)
  //   returns 1: '1,235'
  //   example 2: number_format(1234.56, 2, ',', ' ')
  //   returns 2: '1 234,56'
  //   example 3: number_format(1234.5678, 2, '.', '')
  //   returns 3: '1234.57'
  //   example 4: number_format(67, 2, ',', '.')
  //   returns 4: '67,00'
  //   example 5: number_format(1000)
  //   returns 5: '1,000'
  //   example 6: number_format(67.311, 2)
  //   returns 6: '67.31'
  //   example 7: number_format(1000.55, 1)
  //   returns 7: '1,000.6'
  //   example 8: number_format(67000, 5, ',', '.')
  //   returns 8: '67.000,00000'
  //   example 9: number_format(0.9, 0)
  //   returns 9: '1'
  //  example 10: number_format('1.20', 2)
  //  returns 10: '1.20'
  //  example 11: number_format('1.20', 4)
  //  returns 11: '1.2000'
  //  example 12: number_format('1.2000', 3)
  //  returns 12: '1.200'
  //  example 13: number_format('1 000,50', 2, '.', ' ')
  //  returns 13: '100 050.00'
  //  example 14: number_format(1e-8, 8, '.', '')
  //  returns 14: '0.00000001'

  number = ( number + '').replace ( /[^0-9+\-Ee.]/g, '');
  var n = ! isFinite ( +number) ? 0 : +number;
  var prec = ! isFinite ( +decimals) ? 0 : Math.abs ( decimals);
  var sep = ( typeof thousandsSep === 'undefined') ? ',' : thousandsSep;
  var dec = ( typeof decPoint === 'undefined') ? '.' : decPoint;
  var s = '';

  var toFixedFix = function ( n, prec)
  {
    var k = Math.pow ( 10, prec);
    return '' + ( Math.round ( n * k) / k).toFixed ( prec);
  }

  // @todo: for IE parseFloat(0.55).toFixed(0) = 0;
  s = ( prec ? toFixedFix ( n, prec) : '' + Math.round ( n)).split ( '.');
  if ( s[0].length > 3)
  {
    s[0] = s[0].replace ( /\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ( ( s[1] || '').length < prec)
  {
    s[1] = s[1] || '';
    s[1] += new Array ( prec - s[1].length + 1).join ( '0');
  }

  return s.join ( dec);
}

function Uint8ArrayToHexString ( ui8array)
{
  var hexstring = '', h;
  for ( var i = 0; i < ui8array.length; i++)
  {
    h = ui8array[i].toString ( 16);
    if ( h.length == 1)
    {
      h = '0' + h;
    }
    hexstring += h;
  }
  var p = Math.pow ( 2, Math.ceil ( Math.log2 ( hexstring.length)));
  hexstring = hexstring.padStart ( p, '0');
  return hexstring;
}

/**
 * Delay for a number of milliseconds
 */
function sleep ( delay)
{
  var start = new Date ().getTime ();
  while ( new Date ().getTime () < start + delay);
}

/**
 * Force a browser DOM redraw
 */
function forceRedraw ( element, timeout = 20)
{
  if ( ! element)
  {
    return;
  }

  var n = document.createTextNode ( ' ');
  var disp = element.style.display;

  element.appendChild ( n);
  element.style.display = 'none';

  setTimeout ( function ()
  {
    element.style.display = disp;
    n.parentNode.removeChild ( n);
  }, timeout);
}

/**
 * Print console system banner
 */
console.log ( '%c VoIP Domain \n%c v0.1 - Alpha version ', 'background: #000000; color: #00a0df; font-size: 24px; font-family: Monospace; padding-top: 5px; padding-bottom: 5px; text-align: center; display: block;', 'background: #00a0df; color: #ffffff; font-size: 12px; padding-top: 5px; padding-bottom: 5px; display: block; position: relative; text-align: center;');
