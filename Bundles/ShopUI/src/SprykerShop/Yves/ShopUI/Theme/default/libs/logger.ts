export enum LogLevel {
    ERRORS_ONLY = 0,
    VERBOSE,
    DEBUG
}

const DUMMY_FUNCTION = function () { };

export default class Logger {
    readonly prefix: string
    readonly level: LogLevel

    constructor(prefix: string, level: LogLevel = LogLevel.ERRORS_ONLY) {
        this.prefix = `[${prefix}]`;
        this.level = level;

        if (this.level < LogLevel.DEBUG) { 
            this.debug = DUMMY_FUNCTION;
        }

        if (this.level < LogLevel.VERBOSE) {
            this.log = DUMMY_FUNCTION;
        }
    }

    debug(...args): void {
        console.log(this.prefix, ...args);
    }

    log(...args): void {
        console.log(this.prefix, ...args);
    }

    error(...args): void {
        console.error(this.prefix, ...args);
    }

}
