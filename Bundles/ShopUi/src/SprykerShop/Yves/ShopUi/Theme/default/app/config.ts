declare const __NAME__: string;
declare const __PRODUCTION__: boolean;
import { LogLevel } from './logger';

/**
 * Defines the application configuration.
 *
 * @module Config
 */

let applicationConfig: Config;

/**
 * Defines the structure of the application configuration object.
 *
 * @remarks
 * events.ready is deprecated; please use events.mount instead.
 */
export interface Config {
    readonly name: string
    readonly isProduction: boolean

    events: {
        mount: string
        ready: string // deprecated
        bootstrap: string
        error: string
    }

    log: {
        prefix: string
        level: LogLevel
    },

    extra?: any
}

/**
 * Defines the default application configuration object.
 *
 * @remarks
 * events.ready is deprecated; please use events.mount instead.
 */
export const defaultConfig: Config = {
    name: __NAME__,
    isProduction: __PRODUCTION__,

    events: {
        mount: 'components-mount',
        ready: 'components-ready', // deprecated
        bootstrap: 'application-bootstrap',
        error: 'application-error'
    },

    log: {
        prefix: __NAME__,
        level: __PRODUCTION__ ? LogLevel.ERRORS_ONLY : LogLevel.VERBOSE
    }
}

/**
 * Sets a new configuration.
 *
 * @param config New configuration to set.
 */
export function set(config: Config): void {
    applicationConfig = config;
}

/**
 * Gets the current configuration.
 *
 * @returns The current configuration.
 */
export function get(): Config {
    return applicationConfig;
}
