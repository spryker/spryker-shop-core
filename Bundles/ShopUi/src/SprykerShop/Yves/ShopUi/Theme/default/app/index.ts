import Candidate from './candidate';
import { LogLevel, log, info, error, config as setLogConfig } from './logger';
import { candidates } from './registry';
import { get as config, set as setConfig, defaultConfig, Config } from './config';

function fireReadyEvent(): void {
    const readyEvent = new CustomEvent(config().events.ready);
    document.dispatchEvent(readyEvent);
}

function fireErrorEvent(err: Error): void {
    const errorEvent = new CustomEvent(config().events.error, { detail: err });
    document.dispatchEvent(errorEvent);
}

export async function mount(): Promise<void[]> {
    return Promise.all(candidates().map((candidate: Candidate) => candidate.mount()));
}

async function onDOMContentLoaded(): Promise<void> {
    log('DOM loaded');

    try {
        await mount();
        fireReadyEvent();
        log('application ready');
    } catch (err) {
        fireErrorEvent(err);
        error('application error\n', err);
    }
}

export async function bootstrap(appConfig: Config = defaultConfig): Promise<void> {
    setConfig(appConfig);
    setLogConfig(config().log.level, config().log.prefix);
    info('mode:', config().isProduction ? 'PRODUCTION,' : 'DEVELOPMENT,', 'log-level:', LogLevel[config().log.level]);

    document.addEventListener('DOMContentLoaded', () => onDOMContentLoaded());
}
