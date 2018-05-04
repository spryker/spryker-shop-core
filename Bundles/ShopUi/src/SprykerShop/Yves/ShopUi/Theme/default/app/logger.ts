import { LogLevel, Config } from './config';

const VOID_FUNCTION = function () { };

export default class Logger {
    protected readonly config: Config

    constructor(config: Config) {
        this.config = config;

        if (this.config.log.level < LogLevel.VERBOSE) {
            this.debug = VOID_FUNCTION;
        }

        if (this.config.log.level < LogLevel.DEFAULT) {
            this.log = VOID_FUNCTION;
            this.info = VOID_FUNCTION;
            this.warn = VOID_FUNCTION;
        }

        this.log('mode:', this.config.isProduction ? 'PRODUCTION,' : 'DEVELOPMENT,', 'log-level:', LogLevel[this.config.log.level]);
    }

    protected prefix(type: string): string {
        return `[${this.config.log.prefix}@${type}]`;
    }

    debug(...args): void {
        console.debug(this.prefix('debug'), ...args);
    }

    log(...args): void {
        console.log(this.prefix('log'), ...args);
    }

    info(...args): void {
        console.info(this.prefix('info'), ...args);
    }

    warn(...args): void {
        console.warn(this.prefix('warn'), ...args);
    }

    error(...args): void {
        console.error(this.prefix('error'), ...args);
    }

}
