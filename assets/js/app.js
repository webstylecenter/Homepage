window.$ = window.jQuery = require('jquery');
require('jquery-ui');
require('jquery-jscroll');
require('jquery-modal');
require('spectrum-colorpicker');
require('jquery-hammerjs');
window.Handlebars = require('handlebars/dist/handlebars.min.js');
window.ClipboardJS = require('clipboard');

import { WOW } from 'wowjs';

import 'handlebars/dist/handlebars.min.js';

/** global: WOW */
new WOW({
    scrollContainer: '.scroll',
    mobile: false
}).init();

require('./components/main');
require('./components/checklist');
require('./components/droplist');
require('./components/newsfeedlist');
require('./components/screensaver');
require('./components/searchbox');
require('./components/settings');
require('./components/welcome');
require('./components/register');
