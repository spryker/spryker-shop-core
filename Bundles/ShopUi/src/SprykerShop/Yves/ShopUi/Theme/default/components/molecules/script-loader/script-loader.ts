import Component from '../../../models/component';

const EVENT_LOADED = 'loaded';

export default class ScriptLoader extends Component {
    readonly scriptSource: string
    readonly ignoredAttributes: string[]

    constructor() { 
        super();

        this.ignoredAttributes = [
            'class',
            'data-qa'
        ];
    }

    protected readyCallback(): void {
        this.appendScriptTag();
    }

    protected mapEvents(script) {
        script.addEventListener('load', (event: Event) => {
            this.fireEvent(EVENT_LOADED);
        });
    }

    protected appendScriptTag(): void {
        const head = document.querySelector('head');
        const script = this.createScriptTag();

        head.appendChild(script);
    }

    protected createScriptTag(): HTMLElement {
        let script = document.createElement('script');
        this.mapEvents(script);

        return this.setScriptAttributes(script);
    }

    protected fireEvent(name: string): void {
        const event = new CustomEvent(name);
        this.dispatchEvent(event);
    }

    protected setScriptAttributes(script: HTMLElement): HTMLElement {
        const attributes = this.attributes;

        Array.prototype.forEach.call(attributes, (attribute) => {
            if (!this.isAttributeIgnored(attribute.name)) {
                script.setAttribute(attribute.name, attribute.value);
            }
        });

        return script;
    }

    protected isAttributeIgnored(attributeName: string): boolean {
        return this.ignoredAttributes.indexOf(attributeName) != -1;
    }
}
