import Component from 'ShopUi/models/component';
import AjaxProvider from 'ShopUi/components/molecules/ajax-provider/ajax-provider';
import QuickOrderFormField from '../quick-order-form-field/quick-order-form-field';

export default class OrderMeasurementUnit extends Component {
    ajaxProvider: AjaxProvider;
    wrapper: HTMLElement;
    currentFieldComponent: QuickOrderFormField;

    protected readyCallback(): void {
        this.wrapper = <HTMLElement>this.querySelector(`.${this.jsName}__wrapper`);
        this.currentFieldComponent = <QuickOrderFormField>this.closest('quick-order-form-field');

        this.mapEvents();
    }

    private mapEvents(): void {
        this.currentFieldComponent.addEventListener('product-loaded-event', () => this.onLoaded());
        this.currentFieldComponent.addEventListener('product-delete-event', () => this.onDelete());
    }

    private onLoaded(): void {
        const baseMeasurementUnit = this.currentFieldComponent.productData.baseMeasurementUnit;

        if(baseMeasurementUnit) {
            this.wrapper.innerHTML = baseMeasurementUnit.name;
            return;
        }
    }

    private onDelete(): void {
        this.wrapper.innerHTML = '';
    }

    get ajaxSelector(): string {
        return this.dataset.ajaxSelector;
    }

    get productId(): string {
        return this.currentFieldComponent.productId;
    }
}
