export default class Logger {
    readonly name: string

    constructor(name: string) {
        this.name = name;
    }

    log(...args): void {
        console.log(`${this.name}:`, ...args);
    }

    error(...args): void {
        console.error(`${this.name}:`, ...args);
    }

}
