import Candidate from './candidate';
import { IComponentImporter } from '../models/component';

const registry: Map<string, Candidate> = new Map();

export default function register(tagName: string, importer: IComponentImporter): Candidate {
    const candidate = new Candidate(tagName, importer);
    registry.set(tagName, candidate);
    return candidate;
}

export function entries(): Candidate[] {
    return Array.from(registry.values());
}

export function clear(): void {
    registry.clear();
}
