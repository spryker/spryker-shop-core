import Candidate from './candidate';
import { LogLevel, debug, log, error, config as setLogConfig } from './logger';
import { get as getCandidates } from './registry';
import { get as config, set as setConfig, defaultConfig, Config } from './config';
import Component from '../models/component';

let isBootstrap = true;

function onDOMContentLoaded(): void {
    debug('DOM loaded');
    mount();
}

function onComponentsMount(): void {
    log('components mounted');
}

function onApplicationBootstrap(): void {
    log('application bootstrap completed');
}

function onApplicationError(e: CustomEvent): void {
    error('application error ->', e.detail);
}

/* tslint:disable: no-any */
function dispatchCustomEvent(name: string, detail: any = {}): void {
    const event = new CustomEvent(name, { detail });
    document.dispatchEvent(event);
}
/* tslint:enable */

function mountComponent(component: Component): void {
    component.mountCallback();
    component.markAsMounted();
}

function isComponent(element: Element): boolean {
    // it needs to be changed into `instanceof` check once the following issue get solved:
    // {@link https://github.com/webcomponents/custom-elements/issues/64}
    const component: Component = <Component>element;

    return !!component.name && !!component.jsName;
}

async function mountComponents(): Promise<void> {
    const promises: Array<Promise<Element[]>> = getCandidates().map((candidate: Candidate) => candidate.define());
    const elements: Element[][] = await Promise.all(promises);

    elements.forEach((elementSet: Element[]) => elementSet
        .filter((element: Element) => isComponent(element))
        .filter((component: Component) => !component.isMounted)
        .forEach((component: Component) => mountComponent(component))
    );
}

/**
 * Defines all the webcomponents and mounts all the Spryker components registered in the application.
 * Fires events according to the application status.
 *
 * @remarks
 * This function must be invoked after setup() as it relies on the initial configuration.
 * This function should be invoked on DOMContentLoaded or following event as DOM must be loaded to mount Spryker
 * componets.
 *
 * @event components-mount (config().events.mount) Fired when all components has been succesfully mounted.
 * @event components-ready (config().events.ready) Deprecated, use `components-mount` event instead - Fired when all
 * components has been succesfully mounted.
 * @event application-bootstrap (config().events.bootstrap) Fired only once, when all components has been succesfully
 * mounted for the first time and application bootstrap is completed.
 * @event application-error (config().events.error) Fired when an error occours during the mounting process.
 * @returns Void promise as the mounting process is asyncronous.
 */
export async function mount(): Promise<void> {
    try {
        await mountComponents();
        dispatchCustomEvent(config().events.mount);
        /**
         * @deprecated Use events.mount instead.
         */
        /* tslint:disable: deprecation */
        dispatchCustomEvent(config().events.ready);
        /* tslint:enable: deprecation */

        if (isBootstrap) {
            dispatchCustomEvent(config().events.bootstrap);
            isBootstrap = false;
        }
    } catch (err) {
        dispatchCustomEvent(config().events.error, err);
    }
}

/**
 * Setups the initial configuration for the application and the log system.
 *
 * @param [initialConfig=defaultConfig] The initial configuration. Default development configuration is used if none is
 * passed.
 */
export function setup(initialConfig: Config = defaultConfig): void {
    setConfig(initialConfig);
    setLogConfig(config().log.level, config().log.prefix);

    if (config().isProduction) {
        return;
    }

    log('setup: DEVELOPMENT mode,', LogLevel[config().log.level], 'log');
}

/**
 * Executes the application full bootstrap.
 * It invokes the setup() function first and, on DOMContentLoaded, the mount() function.
 * It adds listeners for all the events (mount, bootstrap and error) emitted  by mount().
 *
 * @remarks
 * This is the recommended function to invoke in order to initialise Spryker Shop frontend application.
 *
 * @param [initialConfig=defaultConfig] The initial configuration. Default development configuration is used if none is
 * passed.
 */
export function bootstrap(initialConfig: Config = defaultConfig): void {
    setup(initialConfig);

    document.addEventListener('DOMContentLoaded', () => onDOMContentLoaded(), { once: true });
    document.addEventListener(config().events.error, (e: CustomEvent) => onApplicationError(e));

    if (config().isProduction) {
        return;
    }

    document.addEventListener(config().events.mount, () => onComponentsMount());
    document.addEventListener(config().events.bootstrap, () => onApplicationBootstrap(), { once: true });
}
