import '@webcomponents/webcomponentsjs/webcomponents-loader';
import '@webcomponents/webcomponentsjs/custom-elements-es5-adapter';

import { bootstrap } from './app';
import './models/component';
import './styles/basics';
import './styles/utils';

document.addEventListener('WebComponentsReady', bootstrap);
