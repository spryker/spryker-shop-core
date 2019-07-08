import Component from 'ShopUi/models/component';
import AjaxProvider from 'ShopUi/components/molecules/ajax-provider/ajax-provider';
import { mount } from 'ShopUi/app';

export default class QuickOrderForm extends Component {
    /**
     * The current form.
     */
    form: HTMLFormElement;

    /**
     * The rows of the current forms.
     */
    rows: HTMLElement;

    /**
     * Element wich creats the for row.
     */
    addRowTrigger: HTMLElement;

    /**
     * Collection of the elements which remove the form row.
     */
    removeRowTriggers: HTMLElement[];

    /**
     * Element creates the AjaxProvider component for the form row.
     */
    addRowAjaxProvider: AjaxProvider;

    /**
     * Element removes the AjaxProvider component from the form row.
     */
    removeRowAjaxProvider: AjaxProvider;

    protected readyCallback(): void {
        this.form = <HTMLFormElement>this.getElementsByClassName(`${this.jsName}__form`)[0];
        this.rows = <HTMLElement>this.getElementsByClassName(`${this.jsName}__rows`)[0];
        this.addRowTrigger = <HTMLElement>this.getElementsByClassName(`${this.jsName}__add-row-trigger`)[0];
        this.addRowAjaxProvider = <AjaxProvider>this.getElementsByClassName(`${this.jsName}__add-row-provider`)[0];
        this.removeRowAjaxProvider = <AjaxProvider>this.getElementsByClassName(
            `${this.jsName}__remove-row-provider`)[0];
        this.registerRemoveRowTriggers();
        this.mapEvents();
    }

    protected registerRemoveRowTriggers(): void {
        this.removeRowTriggers = <HTMLElement[]>Array.from(this.getElementsByClassName(
            `${this.jsName}__remove-row-trigger`));
    }

    protected mapEvents(): void {
        this.addRowTrigger.addEventListener('click', (event: Event) => this.onAddRowClick(event));
        this.mapRemoveRowTriggersEvents();
    }

    protected mapRemoveRowTriggersEvents(): void {
        this.removeRowTriggers.forEach((trigger: HTMLElement) => {
            trigger.addEventListener('click', (event: Event) => this.onRemoveRowClick(event));
        });
    }

    protected onAddRowClick(event: Event): void {
        event.preventDefault();
        this.addRow();
    }

    protected onRemoveRowClick(event: Event): void {
        event.preventDefault();

        const row = <HTMLElement>event.currentTarget;
        const rowIndex = row.getAttribute('data-row-index');
        this.removeRow(rowIndex);
    }

    /**
     * Sends an ajax request to the server and renders the response on the page.
     * @template viod The argument type returned by a successful promise.
     */
    async addRow(): Promise<void> {
        const data = this.getFormData();
        const response = await this.addRowAjaxProvider.fetch(data);

        this.rows.innerHTML = response;
        await mount();
        this.registerRemoveRowTriggers();
        this.mapRemoveRowTriggersEvents();
    }

    /**
     * Sends an ajax request to the server and renders the response on the page.
     * @template viod The argument type returned by a successful promise.
     * @param rowIndex A row string index used for removal.
     */
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

    /**
     * Gets an instance of the FormData.
     * @template FormData A data type returned by the function.
     * @param appendData An optional data object for extension of the returned data.
     * @returns A data instance of the FormData type.
     */
    getFormData(appendData?: object): FormData {
        const data = new FormData(this.form);

        if (appendData) {
            Object.keys(appendData).forEach((key: string) => data.append(key, appendData[key]));
        }

        return data;
    }
}
