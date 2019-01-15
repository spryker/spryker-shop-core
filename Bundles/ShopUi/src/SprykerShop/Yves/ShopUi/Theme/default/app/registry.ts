import Candidate from './candidate';

interface HTMLElementContructor {
    new(): HTMLElement
}

export type CustomElementContructor = HTMLElementContructor;

export interface CustomElementImporter {
    (): Promise<{ default: CustomElementContructor }>
}

const registry: Map<string, Candidate> = new Map();

export default function register(tagName: string, customElementImporter: CustomElementImporter): Candidate {
    const candidate = new Candidate(tagName, customElementImporter);
    registry.set(tagName, candidate);
    return candidate;
}

export function unregister(tagName: string): boolean {
    return registry.delete(tagName);
}

export function candidates(): Candidate[] {
    return Array.from(registry.values());
}
