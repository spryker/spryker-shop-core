import Logger from './libs/logger';
import Candidate from './libs/candidate';
import Bootstrapper from './libs/bootstrapper';
import { IComponentImporter } from './models/component';

export const EVENT_APP_READY: string = 'app.ready';
export const EVENT_APP_ERROR: string = 'app.error';

const registry: Map<string, Candidate> = new Map();
const logger: Logger = new Logger('Yves default UI');
const boostrapper: Bootstrapper = new Bootstrapper();

function ready(): void {
    const readyEvent = new CustomEvent(EVENT_APP_READY);
    document.dispatchEvent(readyEvent);
}

function fail(error): void {
    const errorEvent = new CustomEvent(EVENT_APP_ERROR, { detail: error });
    document.dispatchEvent(errorEvent);
    logger.log('application bootstrap failed\n', error);
}

export function register(name: string, importer: IComponentImporter): Candidate {
    const selector = `js-${name}`;
    const candidate = new Candidate(selector, importer);
    registry.set(selector, candidate);
    return candidate;
}

export function bootstrap(): void {
    console.log(registry);

    boostrapper
        .start(registry)
        .then(ready)
        .catch(fail);
}
