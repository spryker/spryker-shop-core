declare const __NAME__: string;
declare const __PRODUCTION__: boolean;
import { LogLevel } from './logger';

/* tslint:disable: no-redundant-jsdoc */
/**
 * Defines the application configuration.
 *
 * @module Config
 */
/* tslint:enable */

let applicationConfig: Config;

/**
 * Defines the structure of the application configuration object.
 */
/* tslint:disable: no-any */
export interface Config {
    readonly name: string;
    readonly isProduction: boolean;

    events: {
        mount: string
        /**
         * @deprecated Use events.mount instead.
         */
        ready: string
        bootstrap: string
        error: string
    };

    log: {
        prefix: string
        level: LogLevel
    };

    extra?: any;
}
/* tslint:enable */

/**
 * Defines the default application configuration object.
 */
export const defaultConfig: Config = {
    name: __NAME__,
    isProduction: __PRODUCTION__,

    events: {
        mount: 'components-mount',
        /**
         * @deprecated Use events.mount instead.
         */
        ready: 'components-ready',
        bootstrap: 'application-bootstrap',
        error: 'application-error'
    },

    log: {
        prefix: __NAME__,
        level: __PRODUCTION__ ? LogLevel.ERRORS_ONLY : LogLevel.VERBOSE
    }
};

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
