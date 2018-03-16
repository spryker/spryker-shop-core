declare const __NAME__: string;
declare const __PRODUCTION__: boolean;

export enum AppLogLevel {
    ERRORS_ONLY = 0,
    DEFAULT,
    VERBOSE
}

export interface AppConfig { 
    events: {
        ready: string
        error: string
    }

    log: {
        prefix: string
        level: AppLogLevel
    },

    extra?: any
}

export default <AppConfig>{
    events: {
        ready: 'app-ready',
        error: 'app-error'
    },

    log: {
        prefix: __NAME__,
        level: __PRODUCTION__ ? AppLogLevel.ERRORS_ONLY : AppLogLevel.VERBOSE
    }
}
