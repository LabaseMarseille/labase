/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';
const $ = require('jquery');
// create global $ and jQuery variables
global.$ = global.jQuery = $;
// start the Stimulus application
import './bootstrap';
import 'bootstrap-table';
import 'bootstrap-table/dist/locale/bootstrap-table-fr-FR.js';
import 'bootstrap-table/dist/extensions/filter-control/bootstrap-table-filter-control';
import 'bootstrap-table/dist/extensions/multiple-sort/bootstrap-table-multiple-sort';
import 'bootstrap-table/dist/extensions/sticky-header/bootstrap-table-sticky-header.min.js';
import 'bootstrap-table/dist/extensions/fixed-columns/bootstrap-table-fixed-columns.min.js';
import 'tableexport.jquery.plugin';
import 'bootstrap-table/dist/extensions/export/bootstrap-table-export';
import interactionPlugin from '@fullcalendar/interaction'
import '@fullcalendar/core'
import '@fullcalendar/interaction'
import '@fullcalendar/daygrid'


require('bootstrap');
// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');

require('select2')
$('select').select2();


require('bootstrap-datepicker/js/bootstrap-datepicker')
require('bootstrap-datepicker/js/locales/bootstrap-datepicker.fr')
require('bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')
$(document).ready(function (){
    $('.js-datepickera').datepicker({
        format: 'dd/mm/YYYY'
    });



});

