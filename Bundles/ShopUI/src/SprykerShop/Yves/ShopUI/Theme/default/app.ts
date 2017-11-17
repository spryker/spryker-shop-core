import Logger from './libs/logger';
import Registry from './libs/registry';
import Candidate from './libs/candidate';
import Bootstrapper from './libs/bootstrapper';
import { IComponentImporter } from './models/component';

export const EVENT_APP_READY: string = 'app.ready';
export const EVENT_APP_ERROR: string = 'app.error';

const logger: Logger = new Logger('Yves default UI');
const registry: Registry = new Registry();
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

export function register(selector: string, importer: IComponentImporter): Candidate {
    const candidate = new Candidate(selector, importer);
    registry.add(candidate);
    return candidate;
}

export function bootstrap(): void {
    boostrapper
        .start(registry)
        .then(ready)
        .catch(fail);
}
