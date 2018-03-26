import Logger from './logger';
import App from './app';
import defaultAppConfig from './config';

const logger = new Logger(defaultAppConfig);
const app = new App(defaultAppConfig, logger);

document.addEventListener('WebComponentsReady', () => app.bootstrap());
