import Candidate from './candidate';
import { LogLevel, debug, log, info, error, config as setLogConfig } from './logger';
import { candidates } from './registry';
import { get as config, set as setConfig, defaultConfig, Config } from './config';

function fireComponentReadyEvent(): void {
    const readyEvent = new CustomEvent(config().events.ready);
    document.dispatchEvent(readyEvent);
}

function fireApplicationBootstrapCompletedEvent(): void {
    const bootstrapEvent = new CustomEvent(config().events.bootstrap);
    document.dispatchEvent(bootstrapEvent);
}

function fireApplicationErrorEvent(err: Error): void {
    const errorEvent = new CustomEvent(config().events.error, { detail: err });
    document.dispatchEvent(errorEvent);
}

function onDOMContentLoaded(): void {
    debug('DOM loaded');
    mountOnBootstrap();
}

function onApplicationBootstrapCompleted(): void {
    info('bootstrap completed');
}

function onApplicationError(e: CustomEvent): void {
    error('application error ->', e.detail);
}

async function mountCandidates(): Promise<void[]> {
    return Promise.all(candidates().map((candidate: Candidate) => candidate.mount()));
}

async function mountOnBootstrap(): Promise<void> {
    try {
        await mountCandidates();
        fireComponentReadyEvent();
        fireApplicationBootstrapCompletedEvent();
    } catch (err) {
        fireApplicationErrorEvent(err);
        error('bootstrap aborted');
    }
}

export async function mount(): Promise<void> {
    try {
        await mountCandidates();
        log('components mounted and ready');
        fireComponentReadyEvent();
    } catch (err) {
        fireApplicationErrorEvent(err);
    }
}

export function setup(appConfig: Config = defaultConfig): void {
    setConfig(appConfig);
    setLogConfig(config().log.level, config().log.prefix);
    log('mode:', config().isProduction ? 'PRODUCTION,' : 'DEVELOPMENT,', 'log-level:', LogLevel[config().log.level]);
}

export function bootstrap(appConfig: Config = defaultConfig): void {
    setup(appConfig);

    document.addEventListener('DOMContentLoaded', () => onDOMContentLoaded(), { once: true });
    document.addEventListener(config().events.bootstrap, () => onApplicationBootstrapCompleted(), { once: true });
    document.addEventListener(config().events.error, (e: CustomEvent) => onApplicationError(e));
}
