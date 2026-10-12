/*
 * JavaScript Templates Runtime
 * https://github.com/blueimp/JavaScript-Templates
 *
 * Copyright 2011, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * https://opensource.org/licenses/MIT
 */

/* global define */

/* eslint-disable strict */

;(function ($) {
  'use strict'
  var tmpl = function (id, data) {
    var f = tmpl.cache[id]
    return data
      ? f(data, tmpl)
      : function (data) {
          return f(data, tmpl)
        }
  }
  tmpl.cache = {'template-upload':function(o,tmpl){var _e=tmpl.encode,_s='\n'; for (var i = 0, file; file = o.files[i]; i++) { _s+='\n  <tr class="template-upload fade">\n    <td>\n      <span class="preview"></span>\n    </td>\n    <td>\n      <p class="name">'+_e(file.name)+'</p>\n      <strong class="error text-danger"></strong>\n    </td>\n    <td>\n      <p class="size">'+_e(o.options.uiProcessing)+'</p>\n      <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>\n    </td>\n    <td>\n      '; if ( ! i && ! o.options.autoUpload) { _s+='\n        <button class="btn btn-primary start" disabled><i class="fa fa-upload"></i> <span>'+_e(o.options.uiUpload)+'</span></button>\n      '; } _s+='\n      '; if ( ! i) { _s+='\n        <button class="btn btn-warning cancel"><i class="fa fa-ban"></i> <span>'+_e(o.options.uiCancel)+'</span></button>\n      '; } _s+='\n    </td>\n  </tr>\n'; } _s+='\n';return _s;}}
  tmpl.encReg = /[<>&"'\x00]/g // eslint-disable-line no-control-regex
  tmpl.encMap = {
    '<': '&lt;',
    '>': '&gt;',
    '&': '&amp;',
    '"': '&quot;',
    "'": '&#39;'
  }
  tmpl.encode = function (s) {
    // eslint-disable-next-line eqeqeq
    return (s == null ? '' : '' + s).replace(tmpl.encReg, function (c) {
      return tmpl.encMap[c] || ''
    })
  }
  if (typeof define === 'function' && define.amd) {
    define(function () {
      return tmpl
    })
  } else if (typeof module === 'object' && module.exports) {
    module.exports = tmpl
  } else {
    $.tmpl = tmpl
  }
})(this)

