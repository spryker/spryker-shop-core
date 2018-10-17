import Candidate from './candidate';
import { LogLevel, debug, log, info, error, config as setLogConfig } from './logger';
import { candidates } from './registry';
import { get as config, set as setConfig, defaultConfig, Config } from './config';
import Component from '../models/component';

function onDOMContentLoaded(): void {
    debug('DOM loaded');
    mountOnBootstrap();
}

function onComponentsReady(): void {
    log('components mounted and ready');
}

function onApplicationBootstrapCompleted(): void {
    info('bootstrap completed');
}

function onApplicationError(e: CustomEvent): void {
    error('application error ->', e.detail);
}

function dispatchCustomEvent(name: string, detail: any = {}): void {
    const event = new CustomEvent(name, { detail });
    document.dispatchEvent(event);
}

async function mountCandidates(): Promise<void> {
    const promises = candidates().map((candidate: Candidate) => candidate.mount());
    const componentSet = await Promise.all(promises);

    componentSet.map((components: Component[]) =>
        components
            .filter((component: Component) => !component.isMounted)
            .map((component: Component) => {
                component.mountCallback();
                component.markAsMounted();
            }));
}

async function mountOnBootstrap(): Promise<void> {
    try {
        await mountCandidates();
        dispatchCustomEvent(config().events.ready);
        dispatchCustomEvent(config().events.bootstrap);
    } catch (err) {
        dispatchCustomEvent(config().events.error, err);
    }
}

export async function mount(): Promise<void> {
    try {
        await mountCandidates();
        dispatchCustomEvent(config().events.ready);
    } catch (err) {
        dispatchCustomEvent(config().events.error, err);
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
    document.addEventListener(config().events.ready, () => onComponentsReady());
    document.addEventListener(config().events.bootstrap, () => onApplicationBootstrapCompleted(), { once: true });
    document.addEventListener(config().events.error, (e: CustomEvent) => onApplicationError(e));
}
