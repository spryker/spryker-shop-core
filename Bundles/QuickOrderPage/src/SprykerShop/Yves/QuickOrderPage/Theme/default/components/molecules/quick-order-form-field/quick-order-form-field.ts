import Component from 'ShopUi/models/component';
import AutocompleteForm from 'ShopUi/components/molecules/autocomplete-form/autocomplete-form';
import MeasurementUnit from '../measurement-unit/measurement-unit';
import OrderItemPrice from '../order-item-price/order-item-price';

export default class QuickOrderForm extends Component {
    autocompleteComponent: HTMLElement;
    measurementUnitComponent: MeasurementUnit;
    orderItemPriceComponent: OrderItemPrice;
    selectedItem: HTMLElement;

    protected readyCallback(): void {
        this.autocompleteComponent = <AutocompleteForm>this.querySelector('autocomplete-form');
        this.measurementUnitComponent = <MeasurementUnit>this.querySelector('measurement-unit');
        this.orderItemPriceComponent = <OrderItemPrice>this.querySelector('order-item-price');

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.addEventListener('click', (event: Event) => this.componentClickHandler(event))
    }

    private componentClickHandler(event: Event): void {
        this.selectedItem = <HTMLElement>event.target;

        if (this.selectedItem.matches(this.dropDownItemSelector)) {
            event.stopPropagation();
            
            this.loadMeasurementUnits();
            this.loadOrderItemPrice();
        }
    }

    private loadMeasurementUnits(): void {
        if (this.measurementUnitComponent) {
            this.measurementUnitComponent.load(this.selectedId);
        }
    }

    private loadOrderItemPrice(): void {
        if (this.orderItemPriceComponent) {
            this.orderItemPriceComponent.addDataIdValue(this.selectedId);
        }
    }

    get dropDownItemSelector(): string {
        return this.autocompleteComponent.getAttribute('item-selector');
    }

    get selectedId(): string {
        return this.selectedItem.getAttribute('data-id-product');
    }
}
