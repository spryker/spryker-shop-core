import Component from 'ShopUi/models/component';

export default class AddressFormToggler extends Component {
    protected toggler: HTMLSelectElement;
    protected targetElementCache: Map<String, HTMLElement> = new Map<String, HTMLElement>();
    form: HTMLFormElement;

    protected readyCallback(): void {
        if(this.triggerSelector) {
            this.toggler = <HTMLSelectElement>document.querySelector(this.triggerSelector);
            this.form = <HTMLFormElement>document.querySelector(this.targetSelector);

            this.prepareTargetElementCache();
            this.onTogglerChange();
            this.mapEvents();
        }
    }

    protected prepareTargetElementCache(): void {
        for (let key in this.triggerOptionToTargetMap) {
            if (!this.triggerOptionToTargetMap.hasOwnProperty(key)) {
                continue;
            }

            const targetSelector = <string>this.triggerOptionToTargetMap[key];
            this.targetElementCache.set(targetSelector, <HTMLElement>document.querySelector(targetSelector));
        }
    }

    protected mapEvents(): void {
        this.toggler.addEventListener('change', (event: Event) => this.onTogglerChange(event));
    }

    protected onTogglerChange(event: Event): void {
        const togglerElement = <HTMLSelectElement>event.srcElement;
        const selectedOptionValue = <string>togglerElement.options[togglerElement.selectedIndex].value;

        this.toggle(selectedOptionValue);
    }

    protected toggle(selectedOptionValue: string): void {
        for (let optionValue in this.triggerOptionToTargetMap) {
            if (!this.triggerOptionToTargetMap.hasOwnProperty(optionValue)) {
                continue;
            }

            const targetSelector = <string>this.triggerOptionToTargetMap[optionValue];
            const isShown = optionValue !== selectedOptionValue;

            this.toggleElement(targetSelector, isShown);
        }
    }

    protected toggleElement(targetSelector: string, isShown: boolean): void {
        const targetElement = this.targetElementCache.get(targetSelector);

        if (targetElement) {
            targetElement.classList.toggle(this.classToToggle, isShown);
        }
    }

    get triggerSelector(): string {
        return this.getAttribute('trigger-selector');
    }

    get targetSelector(): string {
        return this.getAttribute('target-selector');
    }

    get classToToggle(): string {
        return this.getAttribute('class-to-toggle');
    }

    get hasCompanyBusinessUnitAddress(): string {
        return this.getAttribute('has-company-business-unit-address');
    }

    get triggerOptionToTargetMap(): object {
        return JSON.parse(this.getAttribute('trigger-option-to-target-map')) || {'': this.targetSelector};
    }
}
