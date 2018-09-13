import Component from 'ShopUi/models/component';
import AjaxProvider from 'ShopUi/components/molecules/ajax-provider/ajax-provider';
import QuickOrderFormField from '../quick-order-form-field/quick-order-form-field';

interface MeasurementUnitJSON {
    baseMeasurementUnit: {
        code: string,
        defaultPrecision: number,
        idProductMeasurementUnit: number,
        name: string,
    },
    idProductConcrete: number
}

export default class MeasurementUnit extends Component {
    ajaxProvider: AjaxProvider;
    wrapper: HTMLElement;
    currentFieldComponent: QuickOrderFormField;

    protected readyCallback(): void {
        this.ajaxProvider = this.querySelector(this.ajaxSelector);
        this.wrapper = this.querySelector(`.${this.jsName}__wrapper`);
        this.currentFieldComponent = <QuickOrderFormField>this.closest('quick-order-form-field');

        this.mapEvents();
    }

    private mapEvents(): void {
        document.addEventListener('application-bootstrap-completed', () => {
            this.currentFieldComponent.autocompleteForm.hiddenInputElement.addEventListener('addId', () => {
                this.load(this.productId);
            });
        });
    }

    public load(productId: string): void {
        if(productId) {
            this.loadMeasurementUnit(productId);
            return;
        }

        this.measurementUnitContent('');
    }

    async loadMeasurementUnit(productId: string): Promise<void> {
        this.ajaxProvider.queryParams.set('id-product', productId);

        try {
            const response: string = <string>await this.ajaxProvider.fetch();
            const data: MeasurementUnitJSON = <MeasurementUnitJSON>this.generateResponseData(response);

            this.measurementUnitContent(data.baseMeasurementUnit.name);
        } catch (err) {
            throw err;
        }
    }

    private generateResponseData(response: string): MeasurementUnitJSON {
        return Object.assign({}, JSON.parse(response));
    }

    private measurementUnitContent(content: string): void {
        this.wrapper.innerHTML = content;
    }

    get ajaxSelector(): string {
        return this.dataset.ajaxSelector;
    }

    get productId(): string {
        return this.currentFieldComponent.productId;
    }
}
