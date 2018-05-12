/**
 *      jQuery Loader Plugin v1.4.1
 * by Ernani Azevedo <ernaniaz@gmail.com>
 *
 * @name        jQuery Loader
 * @description Loader is a jQuery plugin that loads JS and CSS with dependencies.
 * @version     1.4.1
 * @requires    jQuery 1.8.0 or newer (not testes with older versions, probably works)
 * @author      Ernani Azevedo <ernaniaz@gmail.com>
 * @license     MIT
 */

/**
 * History:
 *
 * v1.0 - Released Nov/04/2013:
 * - First release
 *
 * v1.1 - Released Apr/15/2015:
 * - Fixed IE7, IE8 and IE9 CSS injection
 * - Added support to dependencies into CSS
 * - Added ID tag to JavaScripts
 * - Added ID and Class tags to CSSs
 *
 * v1.2 - Released Oct/27/2015:
 * - Added script sourceURL to JavaScripts (firebug debug facility)
 *
 * v1.3 - Released Jul/11/2017:
 * - Added dependency check when loading
 * - Added onloadfail callback to trigger when something wrong happens at start
 * - Added onsuccess callback to trigger when a script is loaded correctly
 * - Added onfail callback to trigger when a script fail to load
 * - Added setTimeout with 0ms before trigger onfinish, to wait dom load all files
 *
 * v1.4 - Released Jul/18/2017:
 * - Changed way we load JavaScript files, due to Firefox limitations on debug
 *
 * v1.4.1 - Released Jul/20/2017:
 * - Fixed message on error
 */

;( function ( $)
{
  $.loader = function ( options)
  {
    // Sanitize css and js array:
    if ( typeof ( options.js) != 'object')
    {
      options.js = [];
    }
    if ( typeof ( options.css) != 'object')
    {
      options.css = [];
    }

    // Sanitize generic options:
    if ( typeof ( options.cache) == 'boolean')
    {
      $.loader.cache = options.cache;
    }
    if ( typeof ( options.retryLimit) == 'integer')
    {
      $.loader.retryLimit = options.retryLimit;
    }
    if ( typeof ( options.timeout) == 'integer')
    {
      $.loader.timeout = options.timeout;
    }
    if ( typeof ( options.onupdate) == 'function')
    {
      $.loader.onupdate = options.onupdate;
    }
    if ( typeof ( options.onfinish) == 'function')
    {
      $.loader.onfinish = options.onfinish;
    }
    if ( typeof ( options.onrefresh) == 'function')
    {
      $.loader.onrefresh = options.onrefresh;
    }
    if ( typeof ( options.onfail) == 'function')
    {
      $.loader.onfail = options.onfail;
    }
    if ( typeof ( options.onloadfail) == 'function')
    {
      $.loader.onloadfail = options.onloadfail;
    }
    if ( typeof ( options.onsuccess) == 'function')
    {
      $.loader.onsuccess = options.onsuccess;
    }

    // Add javascript scripts to process list:
    var deplist = [];
    for ( i in options.js)
    {
      if ( typeof $.loader.data['js-' + ( typeof ( options.js[i].name) == 'string' ? options.js[i].name : options.js[i].src)] === 'undefined')
      {
        $.loader.data['js-' + ( typeof ( options.js[i].name) == 'string' ? options.js[i].name : options.js[i].src)] = { 'type': 'js', 'status': 'unloaded', 'loaded': false, 'src': options.js[i].src, 'cache': ( typeof ( options.js[i].cache) == 'boolean' ? options.js[i].cache : options.cache), 'dep': ( options.js[i].dep || []), 'id': ( typeof ( options.js[i].id) == 'string' ? options.js[i].id : '')};
        if ( typeof ( options.js[i].dep) == 'object')
        {
          for ( x in options.js[i].dep)
          {
            deplist.push ( options.js[i].dep[x]);
          }
        }
      }
    }

    // Check for every dependency:
    for ( i in deplist)
    {
      if ( typeof $.loader.data['js-' + deplist[i]] != 'object')
      {
        console.warn ( 'JavaScript dependency ' + deplist[i] + ' not found!');
        $.loader.onloadfail ( 'JavaScript dependency ' + deplist[i] + ' not found!');
      }
    }

    // Add css styles to process list:
    var deplist = [];
    for ( i in options.css)
    {
      if ( typeof $.loader.data['css-' + ( typeof ( options.css[i].name) == 'string' ? options.css[i].name : options.css[i].src)] === 'undefined')
      {
        $.loader.data['css-' + ( typeof ( options.css[i].name) == 'string' ? options.css[i].name : options.css[i].src)] = { 'type': 'css', 'status': 'unloaded', 'loaded': false, 'src': options.css[i].src, 'cache': ( typeof ( options.css[i].cache) == 'boolean' ? options.css[i].cache : options.cache), 'media': ( typeof ( options.css[i].media) == 'string' ? options.css[i].media : 'screen, projection'), 'dep': ( options.css[i].dep || []), 'id': ( typeof ( options.css[i].id) == 'string' ? options.css[i].id : ''), 'class': ( typeof ( options.css[i].class) == 'string' ? options.css[i].class : '')};
        if ( typeof ( options.css[i].dep) == 'object')
        {
          for ( x in options.css[i].dep)
          {
            deplist.push ( options.css[i].dep[x]);
          }
        }
      }
    };

    // Check for every dependency:
    for ( i in deplist)
    {
      if ( typeof $.loader.data['css-' + deplist[i]] != 'object')
      {
        console.warn ( 'CSS dependency ' + deplist[i] + ' not found!');
        $.loader.onloadfail ( 'CSS dependency ' + deplist[i] + ' not found!');
      }
    }

    // Initialize and load using the refresh() method:
    $.loader.refresh ();
  };

  // Method to rescan dependencies and process it:
  $.loader.refresh = function ()
  {
    // Check every entry for dependencies:
    var loaded = 0;
    var total = 0;
    for ( i in $.loader.data)
    {
      // Get total of loaded and unloaded scripts:
      if ( $.loader.data[i].status == 'loaded' || $.loader.data[i].status.substring ( 0, 6) == 'failed')
      {
        loaded++;
      }
      total++;

      // If not loaded, check and process it:
      if ( $.loader.data[i].loaded == false)
      {
        var deps = true;
        for ( j in $.loader.data[i].dep)
        {
          if ( $.loader.data[$.loader.data[i].type + '-' + $.loader.data[i].dep[j]].status != 'loaded')
          {
            deps = false;
          }
        }
        if ( deps && $.loader.data[i].status == 'unloaded')
        {
          if ( $.loader.data[i].type == 'js')
          {
            $.loader.loadjs ( i);
          } else {
            $.loader.loadcss ( i);
          }
        }
      }
    };

    // Check if we're staled (no unloaded scripts and still have files to load):
    var unloaded = 0;
    for ( i in $.loader.data)
    {
      if ( $.loader.data[i].status == 'loading')
      {
        unloaded++;
      }
    }
    if ( unloaded == 0 && total != loaded)
    {
      console.warn ( 'Staled state! We still have files to load, but no dependencies satisfied!');
      for ( i in $.loader.data)
      {
        if ( $.loader.data[i].status == 'unloaded')
        {
          console.warn ( 'Script ' + i + ' (' + $.loader.data[i].src + ') staled due to unsatisfied dependency!');
        }
      }
      $.loader.onloadfail ( 'Staled state! We still have files to load, but no dependencies satisfied!');
    }

    // Call client refresh() event:
    $.loader.onrefresh ( loaded, total, ( loaded * 100) / total);

    if ( loaded == total)
    {
      setTimeout ( function () { $.loader.onfinish ( total); }, 0);
    }
  };

  // Method to load javascript script files:
  $.loader.loadjs = function ( name)
  {
    if ( typeof $.loader.data[name].try === 'undefined')
    {
      $.loader.data[name].try = 0;
    }
    $.loader.data[name].status = 'loading';
    $.loader.data[name].loaded = true;
    $.loader.data[name].start = new Date ().getTime ();
    var script = document.createElement ( 'script');
    script.onload = function ()
                    {
                      if ( $.loader.data[name].status == 'loading')
                      {
                        $.loader.data[name].status = 'loaded';
                        $.loader.onsuccess ( name);
                        $.loader.onupdate ( name);
                        $.loader.refresh ();
                      }
                    };
    script.onreadystatechange = function ()
                                {
                                  if ( $.loader.data[name].status == 'loading')
                                  {
                                    if ( script.readyState === 'complete')
                                    {
                                      script.onload ();
                                    } else {
                                      if ( new Date ().getTime () > $.loader.data[name].start + $.loader.timeout)
                                      {
                                        $.loader.data[name].try++;
                                        if ( $.loader.data[name].try > $.loader.retryLimit)
                                        {
                                          script.onerror ( 'max retry reached!');
                                        } else {
                                          $('#' + name).remove ();
                                          $.loader.loadjs ( name);
                                        }
                                      }
                                    }
                                  }
                                };
    script.onerror = function ( textStatus)
                     {
                       if ( $.loader.data[name].status == 'loading')
                       {
                         $.loader.data[name].status = 'failed' + ( typeof textStatus == 'string' && textStatus != '' ? ': ' + textStatus : '');
                         console.log ( 'Loader error on ' + name + ' <' + $.loader.data[name].src + '>' + ( typeof textStatus == 'string' && textStatus != '' ? ': ' + textStatus : ''));
                         $.loader.onfail ( name);
                         $.loader.onupdate ( name);
                         $.loader.refresh ();
                       }
                     };
    script.src = $.loader.data[name].src + ( $.loader.data[name].cache == true ? '?_=' + new Date ().getTime () : '');
    script.id = name;
    document.body.appendChild ( script);
  };

  // Method to load CSS style files:
  $.loader.loadcss = function ( name)
  {
    $.loader.data[name].status = 'loading';
    $.loader.data[name].loaded = true;
    $.ajax (
    {
      type: 'GET',
      url: $.loader.data[name].src,
      dataType: 'text',
      timeout: $.loader.timeout,
      tryCount: 0,
      retryLimit: $.loader.retryLimit,
      cache: $.loader.data[name].cache,
      success: function ( script, textStatus)
               {
                 if ( document.createStyleSheet)
                 {
                   iecss = document.createStyleSheet ( $.loader.data[name].src);
                   iecss.media = $.loader.data[name].media;
                   if ( $.loader.data[name].id != '')
                   {
                     iecss.id = $.loader.data[name].id;
                   }
                   if ( $.loader.data[name].class != '')
                   {
                     iecss.class = $.loader.data[name].class;
                   }
                 } else {
                   $('<link rel="stylesheet" type="text/css" media="' + $.loader.data[name].media + '" href="' + $.loader.data[name].src + '"' + ( $.loader.data[name].id != '' ? ' id="' + $.loader.data[name].id + '"' : '') + ( $.loader.data[name].class != '' ? ' class="' + $.loader.data[name].class + '"' : '') + ' />').appendTo ( 'head');
                 }
                 $.loader.data[name].status = 'loaded';
                 $.loader.onsuccess ( name);
                 $.loader.onupdate ( name);
                 $.loader.refresh ();
               },
      error: function ( jqxhr, textStatus, errorThrown)
             {
               if ( textStatus == 'timeout')
               {
                 this.tryCount++;
                 if ( this.tryCount <= this.retryLimit)
                 {
                   $.ajax ( this);
                   return;
                 }
               }
               $.loader.data[name].status = 'failed: ' + textStatus;
               $.loader.onfail ( name);
               $.loader.onupdate ( name);
               $.loader.refresh ();
             }
    });
  };

  // Global plugin variables:
  $.loader.cache = true;
  $.loader.retryLimit = 3;
  $.loader.timeout = 30000;
  $.loader.data = {};
  $.loader.onfinish = function () {};
  $.loader.onupdate = function () {};
  $.loader.onrefresh = function () {};
  $.loader.onloadfail = function () {};
  $.loader.onfail = function () {};
  $.loader.onsuccess = function () {};
})(jQuery);
