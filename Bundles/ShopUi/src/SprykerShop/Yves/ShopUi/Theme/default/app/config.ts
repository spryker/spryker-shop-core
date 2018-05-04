declare const __NAME__: string;
declare const __PRODUCTION__: boolean;

export enum LogLevel {
    ERRORS_ONLY = 0,
    DEFAULT,
    VERBOSE
}

export interface Config {
    readonly name: string
    readonly isProduction: boolean

    events: {
        ready: string
        error: string
    }

    log: {
        prefix: string
        level: LogLevel
    },

    extra?: any
}

export default <Config>{
    name: __NAME__,
    isProduction: __PRODUCTION__,

    events: {
        ready: 'app-ready',
        error: 'app-error'
    },

    log: {
        prefix: __NAME__,
        level: __PRODUCTION__ ? LogLevel.ERRORS_ONLY : LogLevel.VERBOSE
    }
}
