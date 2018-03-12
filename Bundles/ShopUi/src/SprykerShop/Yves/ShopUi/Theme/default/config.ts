declare const __NAME__: string;
declare const __PRODUCTION__: boolean;

import { LogLevel } from './app/libs/logger';

const config = {
    events: {
        ready: 'app.ready',
        error: 'app.error'
    },

    log: {
        prefix: __NAME__,
        level: __PRODUCTION__ ? LogLevel.ERRORS_ONLY : LogLevel.DEBUG
    }
}

export default config;
