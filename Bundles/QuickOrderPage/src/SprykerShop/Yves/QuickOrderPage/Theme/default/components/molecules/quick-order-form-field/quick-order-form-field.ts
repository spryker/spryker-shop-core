import Component from 'ShopUi/models/component';
import MeasurementUnit from '../measurement-unit/measurement-unit';

export default class QuickOrderForm extends Component {
    autocompleteComponent: HTMLElement;
    measurementUnitComponent: MeasurementUnit;
    selectedItem: HTMLElement;

    protected readyCallback(): void {
        this.autocompleteComponent = this.querySelector('autocomplete-form');
        this.measurementUnitComponent = this.querySelector('measurement-unit');

        this.mapEvents();
    }

    mapEvents(): void {
        this.addEventListener('click', (event: Event) => this.componentClickHandler(event))
    }

    componentClickHandler(event: Event): void {
        this.selectedItem = (<HTMLElement>event.target);

        if(this.selectedItem.matches(this.dropDownItemSelector)) {
            this.measurementUnitComponent.load(this.selectedId);
        }
    }

    get dropDownItemSelector(): string {
        return this.autocompleteComponent.getAttribute('item-selector');
    }

    get selectedId(): string {
        return this.selectedItem.getAttribute('data-id-product');
    }
}
