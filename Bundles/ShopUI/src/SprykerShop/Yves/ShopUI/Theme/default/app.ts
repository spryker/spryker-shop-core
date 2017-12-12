import Logger from './libs/logger';
import Candidate from './libs/candidate';
import Bootstrapper from './libs/bootstrapper';
import { IComponentImporter } from './models/component';

import config from './app.config';

const registry: Map<string, Candidate> = new Map();
const logger: Logger = new Logger(config.log.prefix, config.log.level);
const boostrapper: Bootstrapper = new Bootstrapper(logger);

function ready(): void {
    const readyEvent = new CustomEvent(config.events.ready);
    document.dispatchEvent(readyEvent);
}

function fail(error): void {
    const errorEvent = new CustomEvent(config.events.error, { detail: error });
    document.dispatchEvent(errorEvent);
    logger.log('application bootstrap failed\n', error);
}

export function register(tag: string, importer: IComponentImporter): Candidate {
    const candidate = new Candidate(tag, importer, logger);
    registry.set(tag, candidate);
    return candidate;
}

export function bootstrap(): void {
    boostrapper
        .start(registry)
        .then(ready)
        .catch(fail);
}
