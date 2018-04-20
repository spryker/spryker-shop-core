import Component from 'ShopUi/models/component';
import AjaxProvider from 'ShopUi/components/molecules/ajax-provider/ajax-provider';

export default class QuickOrderForm extends Component {
    form: HTMLFormElement
    fieldList: HTMLElement
    addRowTrigger: HTMLElement
    removeRowTriggers: HTMLElement[]
    addRowAjaxProvider: AjaxProvider
    removeRowAjaxProvider: AjaxProvider

    async readyCallback() {
        this.form = <HTMLFormElement>this.querySelector(`.${this.componentSelector}__form`);
        this.fieldList = <HTMLElement>this.querySelector(`.${this.componentSelector}__list`);
        this.addRowTrigger = <HTMLElement>this.querySelector(`.${this.componentSelector}__add-row-trigger`);
        this.addRowAjaxProvider = <AjaxProvider>this.querySelector(`.${this.componentSelector}__add-row-provider`);
        this.removeRowAjaxProvider = <AjaxProvider>this.querySelector(`.${this.componentSelector}__remove-row-provider`);
        this.registerRemoveRowTriggers();
        this.mapEvents();
    }

    registerRemoveRowTriggers() {
        this.removeRowTriggers = <HTMLElement[]>Array.from(this.querySelectorAll(`.${this.componentSelector}__remove-row-trigger`));
    }

    mapEvents() {
        this.addRowTrigger.addEventListener('click', (event: Event) => this.onAddRowClick(event));
        this.mapRemoveRowTriggersEvents();
    }

    mapRemoveRowTriggersEvents() {
        this.removeRowTriggers.forEach((trigger: HTMLElement) => trigger.addEventListener('click', (event: Event) => this.onRemoveRowClick(event)));
    }

    onAddRowClick(event: Event) {
        event.preventDefault();
        this.addRow();
    }

    onRemoveRowClick(event: Event) {
        event.preventDefault();

        const row = <HTMLElement>event.currentTarget;
        const rowIndex = row.getAttribute('data-row-index');
        this.removeRow(rowIndex);
    }

    async addRow(): Promise<void> {
        const data = this.getFormData();
        const response = await this.addRowAjaxProvider.fetch(data);

        this.fieldList.innerHTML = response;
        this.registerRemoveRowTriggers();
        this.mapRemoveRowTriggersEvents();
    }

    async removeRow(rowIndex: string): Promise<void> {
        const data = this.getFormData({
            'row-index': rowIndex
        });
        const response = await this.removeRowAjaxProvider.fetch(data);

        this.fieldList.innerHTML = response;
        this.registerRemoveRowTriggers();
        this.mapRemoveRowTriggersEvents();
    }

    getFormData(appendData?: object): FormData {
        const data = new FormData(this.form);

        if (appendData) {
            Object.keys(appendData).forEach((key: string) => data.append(key, appendData[key]));
        }

        return data;
    }

}
