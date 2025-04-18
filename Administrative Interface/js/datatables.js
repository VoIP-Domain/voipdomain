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
 * VoIP Domain dataTables JavaScript customization script.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Core
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

$(document).ready ( function ()
{
  /**
   * DataTables customization
   */
  if ( typeof $.fn.dataTable == 'function')
  {
    $.extend ( true, $.fn.dataTable.Buttons.defaults,
    {
      dom:
      {
        container:
        {
          tag: 'div',
          className: 'btn-group dt-buttons'
        },
        collection:
        {
          tag: 'div',
          className: 'btn-group dt-button-collection'
        },
        button:
        {
          tag: 'button',
          className: 'btn btn-default',
          active: 'active',
          disabled: 'disabled'
        },
        buttonLiner:
        {
          tag: '',
          className: ''
        }
      }
    });
    $.extend ( true, $.fn.dataTable.defaults,
    {
      order: [[ 1, 'asc' ]],
      processing: true,
      searching: true,
      autoWidth: false,
      responsive: true,
      dom: '<"dt-row"<"dt-left"<"#addbutton">l><"dt-right"f><"dt-center"<B>>>rt+<"dt-row"<"dt-left"i><"dt-right"p>>',
      drawCallback: function ( settings) {},
      mark: true,
      stateSave: true,
      stateLoadParams: function ( settings, data)
      {
        if ( $.urlParam ( 'filter') != null)
        {
          data.search.search = $.urlParam ( 'filter');
        }
      },
      initComplete: function ( settings, json)
      {
        if ( typeof $.fn.stickyTableHeaders == 'function')
        {
          $(this).stickyTableHeaders ( { scrollableArea: $('.wrapper')});
        }
        if ( typeof $.fn.select2 == 'function')
        {
          $('select[name="datatables_length"]').select2 ( { minimumResultsForSearch: Infinity});
        }
        $('.dataTables_filter').empty ().html ( '<label><form><div class="input-group hidden-sm"><input type="search" class="form-control" placeholder="' + VoIP.i18n.__ ( 'Filter...') + '" aria-control="search"><div class="input-group-btn"><button class="btn btn-default" type="reset" data-toggle="tooltip" data-placement="left" title="' + VoIP.i18n.__ ( 'Clear data') + '"><i class="fas fa-times"></i></button></div></div></form></label>');
        $('.dataTables_filter input[type="search"]').val ( settings.oSavedState.search.search).on ( 'keyup', function () { $('#datatables').data ( 'dt').search ( jQuery.fn.DataTable.ext.type.search.string ( this.value)).draw (); }).closest ( 'form').on ( 'submit', function ( e) { e.preventDefault (); return false; });
        $('.dataTables_filter button[type="reset"]').on ( 'click', function () { $('#datatables').data ( 'dt').search ( '').draw (); });
        $('.dataTables').find ( '*[data-toggle="tooltip"]').tooltip ( { container: 'body'});
      },
      buttons:
      [
        {
          extend: 'copyHtml5',
          text: '<i class="far fa-copy"></i>',
          titleAttr: VoIP.i18n.__ ( 'Copy'),
          footer: true,
          exportOptions:
          {
            columns: '.export'
          }
        },
        {
          extend: 'pdfHtml5',
          text: '<i class="far fa-file-pdf"></i>',
          titleAttr: VoIP.i18n.__ ( 'PDF'),
          footer: true,
          exportOptions:
          {
            columns: '.export'
          }
        },
        {
          extend: 'excelHtml5',
          text: '<i class="far fa-file-excel"></i>',
          titleAttr: VoIP.i18n.__ ( 'Excel'),
          footer: true,
          exportOptions:
          {
            columns: '.export'
          }
        },
        {
          extend: 'csvHtml5',
          text: '<i class="far fa-file-alt"></i>',
          titleAttr: VoIP.i18n.__ ( 'CSV'),
          footer: false,
          exportOptions:
          {
            columns: '.export'
          }
        },
        {
          text: '<i class="far fa-file-code"></i>',
          titleAttr: VoIP.i18n.__ ( 'JSON'),
          footer: false,
          action: function ( e, dt, button, config)
                  {
                    $.fn.dataTable.fileSave ( new Blob ( [ JSON.stringify ( dt.buttons.exportData ( { columns: '.export'}))]), $.trim ( $('title').text () + '.json'));
                  }
        },
        {
          extend: 'print',
          text: '<i class="fas fa-print"></i>',
          titleAttr: VoIP.i18n.__ ( 'Print'),
          footer: true,
          exportOptions:
          {
            columns: '.export'
          }
        }
      ],
      language:
      {
        sEmptyTable: VoIP.i18n.__ ( 'No records found'),
        sInfo: VoIP.i18n.__ ( 'Showing _START_ to _END_ of _TOTAL_ records'),
        sInfoEmpty: VoIP.i18n.__ ( 'Showing 0 to 0 of 0 records'),
        sInfoFiltered: '(filtered out of _MAX_ total records)',
        sInfoPostFix: '',
        sInfoThousands: VoIP.i18n.__ ( '.'),
        sLengthMenu: '_MENU_<span class="hidden-xs"> ' + VoIP.i18n.__ ( 'per page') + '</span>',
        sLoadingRecords: VoIP.i18n.__ ( 'Wait...'),
        sProcessing: VoIP.i18n.__ ( 'Processing...'),
        sZeroRecords: VoIP.i18n.__ ( 'No records found'),
        sSearch: '',
        sSearchPlaceholder: VoIP.i18n.__ ( 'Filter'),
        oPaginate:
        {
          sNext: VoIP.i18n.__ ( 'Next'),
          sPrevious: VoIP.i18n.__ ( 'Previous'),
          sFirst: VoIP.i18n.__ ( 'First'),
          sLast: VoIP.i18n.__ ( 'Last')
        },
        oAria:
        {
          sSortAscending: ': ' + VoIP.i18n.__ ( 'Sort columns ascending'),
          sSortDescending: ': ' + VoIP.i18n.__ ( 'Sort columns descending')
        },
        buttons:
        {
          copyTitle: VoIP.i18n.__ ( 'Added to clipboard'),
          copyKeys: VoIP.i18n.__ ( 'Press <i>Ctrl</i> or </i>\u2318</i> + <i>C</i> to copy table to clipboard.<br />To cancel, click on this message or press ESC key.'),
          copySuccess:
          {
            _: VoIP.i18n.__ ( 'Total of %d records'),
            1: VoIP.i18n.__ ( 'Total of 1 record')
          }
        }
      }
    });
  }
});
