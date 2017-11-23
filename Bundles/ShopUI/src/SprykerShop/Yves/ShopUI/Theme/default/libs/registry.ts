import Candidate from './candidate';
import { IComponentImporter } from '../models/component';

export default class Registry {
    readonly candidates: Candidate[]

    constructor() { 
        this.candidates = [];
    }

    add(candidate: Candidate): number {
        return this.candidates.push(candidate);
    }
}
