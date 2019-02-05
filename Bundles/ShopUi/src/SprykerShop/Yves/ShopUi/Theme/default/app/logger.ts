/**
 * Defines the application log layer, manageable by configuration.
 * According to the provided log level, specific logging functions will be voided and related messages hidden.
 *
 * @module Logger
 */

/**
 * Defines the log levels:
 * - ERRORS_ONLY: recommended for production - logs only errors and warnings
 * - DEFAULT: logs everything but debug messages
 * - VERBOSE: logs everything
 */
export enum LogLevel {
    ERRORS_ONLY = 0,
    DEFAULT,
    VERBOSE
}

type LogFunction = (...args: any[]) => void

let prefix: string = '';
const VOID_FUNCTION: LogFunction = function () { };
const getPrefix = (type: string) => `[${prefix}@${type}]`;

/**
 * Configures the log system according the log level and defines the log prefix for every message.
 *
 * @param logLevel The log level used in the application
 * @param logPrefix The log message prefix
 */
export function config(logLevel: LogLevel, logPrefix: string): void {
    prefix = logPrefix;

    if (logLevel < LogLevel.VERBOSE) {
        debug = VOID_FUNCTION;
    }

    if (logLevel < LogLevel.DEFAULT) {
        log = VOID_FUNCTION;
        info = VOID_FUNCTION;
        warn = VOID_FUNCTION;
    }
}

/**
 * Outputs a debug message to the console, but only with VERBOSE log level.
 * This is a wrapper around `console.debug`.
 *
 * @param args List of arguments to log.
 */
export let debug: LogFunction = (...args: any[]): void => {
    console.debug(getPrefix('debug'), ...args);
}

/**
 * Outputs a log message to the console, but only with VERBOSE and DEFAULT log levels.
 * This is a wrapper around `console.log`.
 *
 * @param args List of arguments to log.
 */
export let log: LogFunction = (...args: any[]): void => {
    console.log(getPrefix('log'), ...args);
}

/**
 * Outputs an info message to the console, but only with VERBOSE and DEFAULT log levels.
 * This is a wrapper around `console.info`.
 *
 * @param args List of arguments to log.
 */
export let info: LogFunction = (...args: any[]): void => {
    console.info(getPrefix('info'), ...args);
}

/**
 * Outputs a warn message to the console, but only with VERBOSE and DEFAULT log levels.
 * This is a wrapper around `console.warn`.
 *
 * @param args List of arguments to log.
 */
export let warn: LogFunction = (...args: any[]): void => {
    console.warn(getPrefix('warn'), ...args);
}

/**
 * Outputs an error message to the console, with any given log level.
 * This is a wrapper around `console.error`.
 *
 * @param args List of arguments to log.
 */
export const error: LogFunction = (...args: any[]): void => {
    console.error(getPrefix('error'), ...args);
}
