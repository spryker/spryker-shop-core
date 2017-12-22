import Logger from './libs/logger';
import Candidate, { ICandidateMountStats } from './libs/candidate';
import { IComponentImporter } from './models/component';
import config from './app.config';

const logger: Logger = new Logger(config.log.prefix, config.log.level);
const registry: Map<string, Candidate> = new Map();

function ready(): void {
    const readyEvent = new CustomEvent(config.events.ready);
    logger.log('application ready');
    document.dispatchEvent(readyEvent);
}

function fail(error: Error): void {
    const errorEvent = new CustomEvent(config.events.error, { detail: error });
    logger.error('application bootstrap failed\n', error);
    document.dispatchEvent(errorEvent);
}

export function register(tag: string, importer: IComponentImporter): Candidate {
    const candidate = new Candidate(tag, importer);
    registry.set(tag, candidate);
    return candidate;
}

export async function mount(tag: string): Promise<void> {
    const candidate = registry.get(tag);
    logger.debug('mounting', candidate.count, tag, 'components...');
    return candidate.mount();
}

export async function mountAll(): Promise<void[]> {
    const tags = Array.from(registry.keys());
    return Promise.all(tags.map(mount));
}

export function bootstrap(): void {
    mountAll()
        .then(ready)
        .catch(fail);
}
