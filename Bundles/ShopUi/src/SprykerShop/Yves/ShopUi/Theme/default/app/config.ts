declare const __NAME__: string;
declare const __PRODUCTION__: boolean;
import { LogLevel } from './logger';

/**
 * Defines the structure of the application configuration object.
 */
export interface Config {
    readonly name: string;
    readonly isProduction: boolean;

    events: {
        mount: string;
        /**
         * @deprecated Use events.mount instead.
         */
        ready: string;
        bootstrap: string;
        error: string;
        upgrade: string;
    };

    log: {
        prefix: string;
        level: LogLevel;
    };
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    extra?: any;
}

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
        // eslint-disable-next-line deprecation/deprecation
        ready: 'components-ready',
        bootstrap: 'application-bootstrap',
        error: 'application-error',
        upgrade: 'components-upgrade',
    },

    log: {
        prefix: __NAME__,
        level: __PRODUCTION__ ? LogLevel.ERRORS_ONLY : LogLevel.VERBOSE,
    },
};

/**
 * Defines the application configuration.
 *
 * @module Config
 */

let applicationConfig: Config;

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
