import Component from 'ShopUi/models/component';
import AjaxProvider from 'ShopUi/components/molecules/ajax-provider/ajax-provider';
import { mount } from 'ShopUi/app';

export default class QuickOrderForm extends Component {
    form: HTMLFormElement
    rows: HTMLElement
    addRowTrigger: HTMLElement
    removeAllRowsTrigger: HTMLElement
    removeRowTriggers: HTMLElement[]
    addRowAjaxProvider: AjaxProvider
    removeAllRowsAjaxProvider: AjaxProvider
    removeRowAjaxProvider: AjaxProvider

    protected readyCallback(): void {
        this.form = <HTMLFormElement>this.querySelector(`.${this.jsName}__form`);
        this.rows = <HTMLElement>this.querySelector(`.${this.jsName}__rows`);
        this.addRowTrigger = <HTMLElement>this.querySelector(`.${this.jsName}__add-row-trigger`);
        this.removeAllRowsTrigger = <HTMLElement>this.querySelector(`.${this.jsName}__remove-all-rows-trigger`);
        this.addRowAjaxProvider = <AjaxProvider>this.querySelector(`.${this.jsName}__add-row-provider`);
        this.removeAllRowsAjaxProvider = <AjaxProvider>this.querySelector(`.${this.jsName}__remove-all-rows-provider`);
        this.removeRowAjaxProvider = <AjaxProvider>this.querySelector(`.${this.jsName}__remove-row-provider`);
        this.registerRemoveRowTriggers();
        this.mapEvents();
    }

    protected registerRemoveRowTriggers(): void {
        this.removeRowTriggers = <HTMLElement[]>Array.from(this.querySelectorAll(`.${this.jsName}__remove-row-trigger`));
    }

    protected mapEvents(): void {
        this.addRowTrigger.addEventListener('click', (event: Event) => this.onAddRowClick(event));
        this.removeAllRowsTrigger.addEventListener('click', (event: Event) => this.onRemoveAllRowsClick(event));
        this.mapRemoveRowTriggersEvents();
    }

    protected mapRemoveRowTriggersEvents(): void {
        this.removeRowTriggers.forEach((trigger: HTMLElement) => trigger.addEventListener('click', (event: Event) => this.onRemoveRowClick(event)));
    }

    protected onAddRowClick(event: Event): void {
        event.preventDefault();
        this.addRow();
    }

    protected onRemoveAllRowsClick(event: Event): void {
        event.preventDefault();
        this.removeAllRows();
    }

    protected onRemoveRowClick(event: Event): void {
        event.preventDefault();

        const row = <HTMLElement>event.currentTarget;
        const rowIndex = row.getAttribute('data-row-index');
        this.removeRow(rowIndex);
    }

    async addRow(): Promise<void> {
        const data = this.getFormData();
        const response = await this.addRowAjaxProvider.fetch(data);

        this.rows.innerHTML = response;
        await mount();
        this.registerRemoveRowTriggers();
        this.mapRemoveRowTriggersEvents();
    }

    async removeAllRows(): Promise<void> {
        const response = await this.removeAllRowsAjaxProvider.fetch();

        this.rows.innerHTML = response;
        await mount();
        this.registerRemoveRowTriggers();
        this.mapRemoveRowTriggersEvents();
    }

    async removeRow(rowIndex: string): Promise<void> {
        const data = this.getFormData({
            'row-index': rowIndex
        });
        const response = await this.removeRowAjaxProvider.fetch(data);

        this.rows.innerHTML = response;
        await mount();
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
