import '@webcomponents/webcomponentsjs/webcomponents-loader';
import '@webcomponents/webcomponentsjs/custom-elements-es5-adapter';
import './models/component';
import './styles/basics';
import './styles/utils';
import { bootstrap } from './app';

document.addEventListener('WebComponentsReady', bootstrap);
