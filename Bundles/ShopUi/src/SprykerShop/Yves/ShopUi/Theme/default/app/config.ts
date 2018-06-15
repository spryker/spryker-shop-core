declare const __NAME__: string;
declare const __PRODUCTION__: boolean;
import { LogLevel } from './logger';

let config: Config;

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

export const defaultConfig = <Config>{
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

export function set(newConfig: Config): void {
    config = newConfig;
}

export function get(): Config {
    return config;
}
