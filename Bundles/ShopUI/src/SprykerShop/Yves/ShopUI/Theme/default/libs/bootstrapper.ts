import Logger from './logger';
import Candidate from './candidate';
import Component from '../models/component';

export default class Bootstrapper {

    start(registry: Map<string, Candidate>): Promise<void> { 
        const candidates = Array.from(registry.values());

        return this.mountComponents(candidates)
            .then(this.flattenComponentArrays)
            .then(this.triggerComponentsInit)
            .then(this.triggerComponentsReady);
    }

    async mountComponents(candidates: Candidate[]): Promise<Component[][]> {
        return Promise.all(
            candidates.map(candidate => candidate.mount())
        );
    }

    async flattenComponentArrays(components: Component[][]): Promise<Component[]> {
        return components.reduce(
            (flattenComponentArray, componentArray) => flattenComponentArray.concat(componentArray),
            []
        );
    }

    async triggerComponentsInit(components: Component[]): Promise<Component[]> {
        components.forEach(component => component.init());
        return components;
    }

    async triggerComponentsReady(components: Component[]): Promise<void> {
        components.forEach(component => component.ready());
    }

}
