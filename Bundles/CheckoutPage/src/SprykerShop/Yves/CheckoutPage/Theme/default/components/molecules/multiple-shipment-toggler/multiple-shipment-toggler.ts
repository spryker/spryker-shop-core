import Component from 'ShopUi/models/component';

export default class MultipleShipmentToggler extends Component {
    protected singleShipmentTrigger: HTMLButtonElement;
    protected multipleShipmentTrigger: HTMLButtonElement;
    protected shipmentTypeSelect: HTMLSelectElement;
    protected targets: HTMLElement[];
    protected prevShipmentValue: string;

    protected readyCallback(): void {}

    protected init(): void {
        this.singleShipmentTrigger = <HTMLButtonElement>(
            this.getElementsByClassName(`${this.jsName}__single-shipment-trigger`)[0]
        );
        this.multipleShipmentTrigger = <HTMLButtonElement>(
            this.getElementsByClassName(`${this.jsName}__multiple-shipment-trigger`)[0]
        );
        this.shipmentTypeSelect = <HTMLSelectElement>document.getElementsByClassName(this.selectClassName)[0];
        this.targets = <HTMLElement[]>Array.from(document.getElementsByClassName(this.targetsClassName));

        if (!this.shipmentTypeSelect) {
            return;
        }

        this.updateShipmentTypeSelect();
        this.mapEvents();
    }

    protected updateShipmentTypeSelect(): void {
        const multipleShipmentTypeOption = Array.from(this.shipmentTypeSelect.options).find(
            (item) => item.value === this.multipleShipmentValue,
        );
        multipleShipmentTypeOption.classList.add(this.toggleClassName);
    }

    protected mapEvents(): void {
        this.singleShipmentTrigger.addEventListener('click', () => this.onSingleShipmentTriggerClick());
        this.multipleShipmentTrigger.addEventListener('click', () => this.onMultipleShipmentTriggerClick());
    }

    protected onSingleShipmentTriggerClick(): void {
        this.toggleTarget(false);
        this.shipmentTypeSelect.value = this.prevShipmentValue ? this.prevShipmentValue : this.singleShipmentValue;
        this.dispatchChangeEvent();
        this.updateButtonsState(false);
    }

    protected onMultipleShipmentTriggerClick(): void {
        this.toggleTarget(true);
        this.prevShipmentValue = this.shipmentTypeSelect.value;
        this.shipmentTypeSelect.value = this.multipleShipmentValue;
        this.dispatchChangeEvent();
        this.updateButtonsState(true);
    }

    protected dispatchChangeEvent(): void {
        this.shipmentTypeSelect.dispatchEvent(new Event('change'));
    }

    protected toggleTarget(force?: boolean): void {
        if (!this.targets.length) {
            return;
        }

        this.targets.forEach((target) => target.classList.toggle(this.toggleClassName, force));
    }

    protected updateButtonsState(isMultipleShipmentActive: boolean): void {
        this.singleShipmentTrigger.classList.toggle(this.toggleButtonClassName, isMultipleShipmentActive);
        this.multipleShipmentTrigger.classList.toggle(this.toggleButtonClassName, !isMultipleShipmentActive);
    }

    protected get selectClassName(): string {
        return this.getAttribute('select-class-name');
    }

    protected get targetsClassName(): string {
        return this.getAttribute('toggle-targets-class-name');
    }

    protected get toggleClassName(): string {
        return this.getAttribute('toggle-class-name');
    }

    protected get toggleButtonClassName(): string {
        return this.getAttribute('toggle-button-class-name');
    }

    protected get singleShipmentValue(): string {
        return this.getAttribute('single-shipment-value');
    }

    protected get multipleShipmentValue(): string {
        return this.getAttribute('multiple-shipment-value');
    }
}
