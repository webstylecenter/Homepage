window.$ = window.jQuery = require('jquery');
require('jquery-ui');
require('jquery-jscroll');
require('jquery-modal');
require('spectrum-colorpicker');
require('jquery-hammerjs');
window.Handlebars = require('handlebars/dist/handlebars.min.js');

import { WOW } from 'wowjs';

import 'handlebars/dist/handlebars.min.js';
import './components/fluent';

/** global: WOW */
new WOW({
    scrollContainer: '.scroll',
    mobile: false
}).init();
