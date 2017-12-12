import Logger from './logger';
import Candidate from './candidate';
import Component from '../models/component';

export default class Bootstrapper {
    private readonly logger: Logger

    constructor(logger: Logger) { 
        this.logger = logger;
    }

    async start(registry: Map<string, Candidate>): Promise<void> { 
        const self = this;
        const candidates = Array.from(registry.values());
        
        return this
            .mountComponents(candidates)
            .then(counts => self.logCount(counts));
    }

    async mountComponents(candidates: Candidate[]): Promise<number[]> {
        return Promise.all(
            candidates.map(candidate => candidate.mount())
        );
    }

    async logCount(counts: number[]): Promise<void> {
        const count = counts.reduce((a, b) => a + b);
        this.logger.log(count, 'components mounted');
    }

}
