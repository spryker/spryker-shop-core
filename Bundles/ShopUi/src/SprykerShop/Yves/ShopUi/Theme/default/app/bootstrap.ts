import '../models/component';
import Logger from './logger';
import App from './app';

export default function bootstrap(config) {
    const logger = new Logger(config);
    const app = new App(config, logger);
    document.addEventListener('WebComponentsReady', () => app.bootstrap());
}
