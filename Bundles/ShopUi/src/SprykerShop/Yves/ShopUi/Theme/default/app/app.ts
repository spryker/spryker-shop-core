import Logger from './logger';
import Candidate from './candidate';
import { entries as getCandidateRegistryEntries, clear as clearCandidateRegistry } from './registry';
import { AppConfig } from './config';
import Component from '../models/component';

export default class App { 
    protected readonly config: AppConfig
    protected readonly logger: Logger

    constructor(config: AppConfig, logger: Logger) { 
        this.config = config;
        this.logger = logger;
    }

    protected async mount(candidates: Candidate[]): Promise<Component[][]> {
        return Promise.all(candidates.map((candidate: Candidate) => candidate.mountComponents(this.config, this.logger)));
    }

    protected flatten(components: Component[][]): Component[] {
        return components.reduce((all: Component[], collection: Component[]) => all.concat(collection));
    }

    protected ready(components: Component[]): void {
        components.forEach((component: Component) => component.readyCallback());
        this.fireReadyEvent();
        this.logger.log('application ready');
    }

    protected error(error: Error): void {
        this.fireErrorEvent(error);
        this.logger.error('application bootstrap failed\n', error);
    }

    protected fireReadyEvent() { 
        const readyEvent = new CustomEvent(this.config.events.ready);
        document.dispatchEvent(readyEvent);
    }

    protected fireErrorEvent(error: Error) {
        const errorEvent = new CustomEvent(this.config.events.error, { detail: error });
        document.dispatchEvent(errorEvent);
    }

    async bootstrap() { 
        this.logger.debug('application bootstrap');

        try {
            const candidates = getCandidateRegistryEntries();
            const components = this.flatten(await this.mount(candidates));
            this.ready(components);
        } catch (error) {
            this.error(error);
        } finally { 
            clearCandidateRegistry();
        }
    }
}
