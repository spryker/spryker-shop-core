import Candidate from './candidate';
import { IComponentImporter } from '../models/component';

const registry: Map<string, Candidate> = new Map();

export default function register(name: string, importer: IComponentImporter): Candidate {
    const candidate = new Candidate(name, importer);
    registry.set(name, candidate);
    return candidate;
}

export function candidates(): Candidate[] {
    return Array.from(registry.values());
}
