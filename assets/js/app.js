require('jquery-ui');
require('jquery-jscroll');
require('jquery-modal');
require('spectrum-colorpicker');
require('hammerjs');
require('jquery-hammerjs');

import { WOW } from 'wowjs'
window.WOW = WOW;

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
