jquery.loader
=============
[![GitHub release](https://img.shields.io/github/release/ernaniaz/jquery.loader.svg?maxAge=2592000)](https://github.com/ernaniaz/jquery.loader)
[![GitHub license](https://img.shields.io/github/license/ernaniaz/jquery.loader.svg)](https://github.com/ernaniaz/jquery.loader)

This is a simple plugin that load CSS and JavaScript files into page, with dependencies and progress informations.
It's usefull to modern web based systems that didn't reload the page, and load all the required libraries at first page access.

You can access the demo at https://ernaniaz.github.io/jquery.loader/demo.html.

Features
--------
* Allow code dependency load;
* Support progress monitor;
* Callback after all scripts loaded;
* Load JavaScript and CSS files.

Basic Usage
-----------
```javascript
$.loader (
{
  // Add array with JavaScript file list to be loaded. Structure must has 'name', 'src' and 'dep' informations.
  js: [
        {
          name: 'main-javascript',
          src: 'js/main-javascript.js',
          dep: [
                 'submodule-1',
                 'submodule-2'
               ]
        },
        {
          name: 'submodule-1',
          src: 'js/submodule1.js',
          dep: [
                 'submodule-3'
               ]
        },
        {
          name: 'submodule-2',
          src: 'js/submodule2.js',
          dep: []
        },
        {
          name: 'submodule-3',
          src: 'js/submodule3.js',
          dep: []
        }
      ],
  // Add array with CSS file list to be loaded. Structure must has 'name', 'src' and 'dep' informations.
  css: [
         {
           name: 'main-css',
           src: 'css/main-css.css',
           dep: []
         }
       ],
  // Should permit or not the use of cache. If false, will be added ?_(TIMESTAMP NUMBER) to URL, to avoid browser cache.
  cache: false,
  // Number of retries in case of failure. 0 will disable retry.
  retryLimit: 3,
  // Timeout in miliseconds to wait for script load. 0 will disable timeout.
  timeout: 0,
  // Callback function to be executed every time a script is loaded or failed to be loaded. Parameters will be the name of the script.
  onupdate: function ( script)
            {
              console.log ( 'onupdate fired to script ' + script);
            },
  // Callback function to be executed every time a script is loaded. Parameters will be the number of loaded files, total of files and percentage loaded.
  onrefresh: function ( loaded, total, percentage)
             {
               $('.percentage').animate ( { width: percentage + '%'}, 50);
             },
  // Callback function to be executed when 100% of files was loaded. Parameter will be the total of files loaded.
  onfinish: function ( total)
            {
              $('.application').trigger ( 'start');
            },
  // Callback function to be executed if there's any dependency error at load. Added in version 1.3.
  onloadfail: function ( error)
              {
                console.warn ( error);
              },
  // Callback function to be executed everytime a script is loaded successfully. Added in version 1.3.
  onsuccess: function ( name)
             {
               console.log ( 'Script ' + name + ' loaded.');
             },
  // Callback function to be executed everytime a script fail to load. Added in version 1.3.
  onfail: function ( name)
          {
            console.error ( 'Script ' + name + ' FAILED to load!');
          }
});
```
Dependencies
------------
* [jQuery](http://jquery.com/)

History
-------
This plugin was created when I need to create a system with modular support and that need to load JavaScript code with dependencies to correct load order.

v1.0 - Released Nov/04/2013:
* First release.

v1.1 - Released Apr/15/2015:
* Fixed IE7, IE8 and IE9 CSS injection;
* Added support to dependencies into CSS;
* Added ID tag to JavaScripts;
* Added ID and Class tags to CSSs.

v1.2 - Released Oct/27/2015:
* Added script sourceURL to JavaScripts (firebug debug facility).

v1.3 - Released Jul/11/2017:
* Added dependency check when loading;
* Added onloadfail callback to trigger when something wrong happens at start;
* Added onsuccess callback to trigger when a script is loaded correctly;
* Added onfail callback to trigger when a script fail to load.

v1.4 - Released Jul/18/2017:
* Changed way we load JavaScript files, due to Firefox limitations on debug.

v1.4.1 - Released Jul/20/2017:
* Fixed message on error.

Note: The release date was original date I wrote and versioned this script. I just published it now with MIT license!

License
-------
MIT License.
