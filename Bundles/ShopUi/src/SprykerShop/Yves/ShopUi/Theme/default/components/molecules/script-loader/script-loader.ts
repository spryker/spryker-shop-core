import Component from '../../../models/component';
import { debug } from '../../../app/logger';

const EVENT_SCRIPT_LOAD = 'scriptload';
const defaultIgnoredAttributes = [
    'class',
    'data-qa'
];

/**
 * @event scriptload An event which is triggered when a script is loaded.
 */
export default class ScriptLoader extends Component {
    /**
     * The <head> tag on the page.
     */
    head: HTMLHeadElement;

    /**
     * The <script> tag o the page.
     */
    script: HTMLScriptElement;

    protected readyCallback(): void {
        this.script = <HTMLScriptElement>document.querySelector(`script[src="${this.src}"]`);

        if (!!this.script) {
            this.mapEvents();
            debug(`${this.name}: "${this.src}" is already in the DOM`);

            return;
        }

        this.head = <HTMLHeadElement>document.getElementsByTagName('head')[0];
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

    /**
     * Gets the array of ignored attributes that are not copied from the current component
     * to the script tag when created.
     */
    get ignoredAttributes(): string[] {
        return [
            ...defaultIgnoredAttributes
        ];
    }

    /**
     * Gets if the script already exists in DOM.
     */
    get isScriptAlreadyInDOM(): boolean {
        return !!document.querySelector(`script[src="${this.src}"]`);
    }

    /**
     * Gets the url endpoint used to load the script.
     */
    get src(): string {
        return this.getAttribute('src');
    }
}
