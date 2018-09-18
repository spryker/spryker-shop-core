import Component from 'ShopUi/models/component';
import AjaxProvider from 'ShopUi/components/molecules/ajax-provider/ajax-provider';

interface MeasurementUnitJSON {
    baseMeasurementUnit: {
        code: string,
        defaultPrecision: Number,
        idProductMeasurementUnit: Number,
        name: string,
    },
    idProductConcrete: Number
}

export default class MeasurementUnit extends Component {
    ajaxProvider: AjaxProvider;
    selectedItemId: String;
    wrapper: HTMLElement;

    protected readyCallback(): void {
        this.ajaxProvider = this.querySelector(this.ajaxSelector);
        this.wrapper = this.querySelector(`.${this.jsName}__wrapper`);
    }

    public load(id: string): void {
        this.loadMeasurementUnit(id);
    }

    async loadMeasurementUnit(id: string): Promise<void> {
        this.ajaxProvider.queryParams.set('id-product', id);

        try {
            const response: string = await this.ajaxProvider.fetch();
            const data: MeasurementUnitJSON = this.generateResponseData(response);

            this.addMeasurementUnit(data.baseMeasurementUnit.name);
        } catch (err) {
            console.log(err);
        }
    }

    private generateResponseData(response: string): MeasurementUnitJSON {
        return Object.assign({}, JSON.parse(response));
    }

    protected addMeasurementUnit(content: string) {
        this.wrapper.innerHTML = content;
    }

    get ajaxSelector(): string {
        return this.dataset.ajaxSelector;
    }
}
