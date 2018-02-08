declare const PRODUCTION: boolean;

import { LogLevel } from './libs/logger';

const config = {
    events: {
        ready: 'app.ready',
        error: 'app.error'
    },

    log: {
        prefix: 'yves-ui',
        level: PRODUCTION ? LogLevel.ERRORS_ONLY : LogLevel.DEBUG
    }
}

export default config;
