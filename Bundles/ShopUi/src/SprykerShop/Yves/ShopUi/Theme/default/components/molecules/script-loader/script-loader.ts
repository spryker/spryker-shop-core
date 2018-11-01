import Component from '../../../models/component';
import { debug } from '../../../app/logger';

const EVENT_SCRIPT_LOAD = 'scriptload';
const defaultIgnoredAttributes = [
    'class',
    'data-qa'
];

export default class ScriptLoader extends Component {
    head: HTMLHeadElement
    script: HTMLScriptElement

    protected readyCallback(): void {
        this.script = <HTMLScriptElement>document.querySelector(`script[src="${this.src}"]`);

        if (!!this.script) {
            this.mapEvents();
            debug(`${this.name}: "${this.src}" is already in the DOM`);
            return;
        }

        this.head = <HTMLHeadElement>document.querySelector('head');
        this.script = <HTMLScriptElement>document.createElement('script');

        this.mapEvents();
        this.setScriptAttributes();
        this.appendScriptTag();
    }

    protected mapEvents(): void {
        this.script.addEventListener('load', (event: Event) => this.onScriptLoad(event), { once: true });
    }

    protected onScriptLoad(event: Event): void {
        this.dispatchCustomEvent(EVENT_SCRIPT_LOAD);
    }

    protected setScriptAttributes(): void {
        Array.prototype.forEach.call(this.attributes, (attribute: Attr) => {
            if (!this.isAttributeIgnored(attribute.name)) {
                this.script.setAttribute(attribute.name, attribute.value);
            }
        });
    }

    protected appendScriptTag(): void {
        this.head.appendChild(this.script);
        debug(`${this.name}: "${this.src}" added to the DOM`);
    }

    protected isAttributeIgnored(attributeName: string): boolean {
        return this.ignoredAttributes.indexOf(attributeName) !== -1;
    }

    get ignoredAttributes(): string[] {
        return [
            ...defaultIgnoredAttributes
        ]
    }

    get isScriptAlreadyInDOM(): boolean {
        return !!document.querySelector(`script[src="${this.src}"]`);
    }

    get src(): string {
        return this.getAttribute('src');
    }
}
