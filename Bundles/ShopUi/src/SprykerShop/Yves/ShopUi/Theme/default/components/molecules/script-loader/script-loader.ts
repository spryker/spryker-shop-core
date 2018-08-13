import Component from '../../../models/component';

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

    protected appendScriptTag(): void {
        const head = document.querySelector('head');
        const script = this.createScriptTag();

        head.appendChild(script);
    }

    protected createScriptTag(): HTMLElement {
        let script = document.createElement('script');

        return this.setScriptAttributes(script);
    }

    protected setScriptAttributes(script: HTMLElement): HTMLElement {
        const attributes = this.attributes;

        Array.prototype.forEach.call(attributes, (attribute) => {
            if (this.isAttributeIgnored(attribute.name)) {
                script.setAttribute(attribute.name, attribute.value);
            }
        });

        return script;
    }

    protected isAttributeIgnored(attributeName: string): boolean {
        return this.ignoredAttributes.indexOf(attributeName) == -1;
    }
}
