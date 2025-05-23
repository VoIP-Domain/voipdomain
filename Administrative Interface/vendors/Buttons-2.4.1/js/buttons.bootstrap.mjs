/*! Bootstrap integration for DataTables' Buttons
 * © SpryMedia Ltd - datatables.net/license
 */

import jQuery from 'jquery';
import DataTable from 'datatables.net-bs';
import Buttons from 'datatables.net-buttons';

// Allow reassignment of the $ variable
let $ = jQuery;


$.extend(true, DataTable.Buttons.defaults, {
	dom: {
		container: {
			className: 'dt-buttons btn-group flex-wrap'
		},
		button: {
			className: 'btn btn-secondary',
			active: 'active'
		},
		collection: {
			action: {
				dropHtml: '<span class="caret"></span>'
			},
			container: {
				tag: 'div',
				className: 'dt-button-collection',
				content: {
					tag: 'ul',
					className: 'dropdown-menu'
				}
			},
			closeButton: false,
			button: {
				tag: 'li',
				className: 'dt-button',
				active: 'dt-button-active-a',
				disabled: 'disabled',
				liner: {
					tag: 'a'
				},
				spacer: {
					className: 'divider',
					tag: 'li'
				}
			}
		},
		split: {
			action: {
				tag: 'a',
				className: 'btn btn-secondary dt-button-split-drop-button',
				closeButton: false
			},
			dropdown: {
				tag: 'button',
				dropHtml: '<span class="caret"></span>',
				className:
					'btn btn-secondary dt-button-split-drop dropdown-toggle dropdown-toggle-split',
				closeButton: false,
				align: 'split-left',
				splitAlignClass: 'dt-button-split-left'
			},
			wrapper: {
				tag: 'div',
				className: 'dt-button-split btn-group',
				closeButton: false
			}
		}
	}
});


export default DataTable;
