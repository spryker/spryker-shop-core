const VOID_FUNCTION = function () { };
let prefix;

export enum LogLevel {
    ERRORS_ONLY = 0,
    DEFAULT,
    VERBOSE
}

export function config(logLevel: LogLevel, logPrefix: string = 'yves') {
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

function getPrefix(type: string): string {
    return `[${prefix}@${type}]`;
}

export let debug = (...args): void => {
    console.debug(getPrefix('debug'), ...args);
}

export let log = (...args): void => {
    console.log(getPrefix('log'), ...args);
}

export let info = (...args): void => {
    console.info(getPrefix('info'), ...args);
}

export let warn = (...args): void => {
    console.warn(getPrefix('warn'), ...args);
}

export const error = (...args): void => {
    console.error(getPrefix('error'), ...args);
}
