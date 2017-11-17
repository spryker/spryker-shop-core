import Logger from './logger';
import Registry from './registry';
import Candidate from './candidate';
import Component from '../models/component';

export default class Bootstrapper {

    start(registry: Registry): Promise<void> { 
        const candidates = registry.getAvailable();

        return this.mountComponents(candidates)
            .then(this.flattenComponentArrays)
            .then(this.triggerComponentsInit)
            .then(this.triggerComponentsReady);
    }

    async mountComponents(candidates: Candidate[]): Promise<Component[][]> {
        return Promise.all(
            candidates.map(candidate => candidate.mountComponents())
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
