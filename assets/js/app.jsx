'use strict';

import 'react';
import 'react-dom';

import './components/like-button';

const e = React.createElement;
const domContainer = document.querySelector('#like_button_container');
ReactDOM.render(e(LikeButton), domContainer);
