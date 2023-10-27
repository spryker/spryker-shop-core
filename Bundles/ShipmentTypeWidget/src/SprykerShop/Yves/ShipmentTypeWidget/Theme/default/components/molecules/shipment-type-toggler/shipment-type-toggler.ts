import Component from 'ShopUi/models/component';

export default class ShipmentTypeToggler extends Component {
    protected triggers: HTMLInputElement[];
    protected defaultShipmentTypeTargets: HTMLElement[];
    protected servicePointTarget: HTMLElement;
    protected billingSameAsShippingContainer: HTMLElement;
    protected billingSameAsShippingInput: HTMLInputElement;

    protected readyCallback(): void {}

    protected init(): void {
        this.triggers = <HTMLInputElement[]>Array.from(this.getElementsByClassName(`${this.jsName}__trigger`));
        this.defaultShipmentTypeTargets = <HTMLElement[]>(
            Array.from(document.getElementsByClassName(this.targetsClassName))
        );
        this.servicePointTarget = <HTMLElement>this.getElementsByClassName(`${this.jsName}__service-point`)[0];
        this.billingSameAsShippingContainer = <HTMLElement>(
            document.getElementsByClassName(this.billingSameAsShippingContainerClassName)[0]
        );
        this.billingSameAsShippingInput = <HTMLInputElement>(
            this.billingSameAsShippingContainer?.getElementsByTagName('input')[0]
        );

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.triggers.forEach((trigger) =>
            trigger.addEventListener('change', (event) => this.onTrigerChange(event.target as HTMLInputElement)),
        );
    }

    protected onTrigerChange(trigger: HTMLInputElement): void {
        const isDefaultShipmentTypeSelected = trigger.value === this.defaultShipmentType;

        this.toggleContentVisibility(isDefaultShipmentTypeSelected);

        if (this.billingSameAsShippingContainer) {
            this.toggleBillingSameAsShipping(isDefaultShipmentTypeSelected);
        }
    }

    protected toggleContentVisibility(isDefaultShipmentTypeSelected?: boolean): void {
        this.defaultShipmentTypeTargets.forEach((target) =>
            target.classList.toggle(this.toggleClassName, !isDefaultShipmentTypeSelected),
        );
        this.servicePointTarget.classList.toggle(this.toggleClassName, isDefaultShipmentTypeSelected);
    }

    protected toggleBillingSameAsShipping(isDefaultShipmentTypeSelected: boolean): void {
        if (isDefaultShipmentTypeSelected) {
            this.billingSameAsShippingContainer.classList.remove(this.toggleClassName);

            return;
        }

        this.billingSameAsShippingContainer.classList.add(this.toggleClassName);

        if (!this.billingSameAsShippingInput) {
            return;
        }

        this.billingSameAsShippingInput.checked = false;
        this.billingSameAsShippingInput.dispatchEvent(new Event('change'));
    }

    protected get defaultShipmentType(): string {
        return this.getAttribute('default-shipment-type');
    }

    protected get targetsClassName(): string {
        return this.getAttribute('delivery-targets-class-name');
    }

    protected get toggleClassName(): string {
        return this.getAttribute('toggle-class-name');
    }

    protected get billingSameAsShippingContainerClassName(): string {
        return this.getAttribute('billing-same-as-shipping-container-class-name');
    }
}
