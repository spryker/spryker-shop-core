import Component from 'ShopUi/models/component';

export default class WindowLocationApplicator extends Component {
    protected form: HTMLFormElement;
    protected triggers: HTMLButtonElement[];

    protected readyCallback(): void {}

    protected init(): void {
        this.form = <HTMLFormElement>document.getElementsByClassName(this.formClassName)[0];
        this.triggers = <HTMLButtonElement[]>Array.from(document.getElementsByClassName(this.triggerClassName));

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.triggers.forEach((element: HTMLButtonElement) => {
            element.addEventListener('click', (event: Event) => this.onTriggerEvent(event));
        });
    }

    protected onTriggerEvent(event: Event): void {
        const categoryUrl = (<HTMLButtonElement>event.currentTarget).getAttribute('data-url');
        /* tslint:disable: no-unbound-method */
        const isFormData = typeof FormData !== 'undefined' && typeof FormData.prototype.get !== 'undefined';
        const isURLSearchParams = typeof URLSearchParams !== 'undefined' && typeof URLSearchParams.prototype.get !== 'undefined';
        /* tslint:enable: no-unbound-method */

        if (isFormData && isURLSearchParams) {
            this.getQueryString(categoryUrl ? categoryUrl : '');

            return;
        }

        this.getQueryStringAlternative(categoryUrl ? categoryUrl : '');
    }

    protected getQueryString(categoryUrl: string = window.location.pathname): void {
        const formData = new FormData(this.form);
        const data = new URLSearchParams(<URLSearchParams>formData);

        formData.forEach((value: string, key: string) => {
            if (value.length) {
                return;
            }

            data.delete(key);
        });

        this.setWindowLocation(categoryUrl, data.toString());
    }

    protected getQueryStringAlternative(categoryUrl: string = window.location.pathname): void {
        const inputFields = <HTMLInputElement[]>Array.from(this.form.getElementsByTagName('input'));
        const selectFields = <HTMLSelectElement[]>Array.from(this.form.getElementsByTagName('select'));

        const filteredInputFields = inputFields.filter((input: HTMLInputElement) => {
            return input.checked || input.type === 'number' || input.type === 'hidden';
        });
        const formFields = [...filteredInputFields, ...selectFields];
        const data: string = formFields.reduce((accumulator: string, field: HTMLInputElement | HTMLSelectElement) => {
            if (field.name && field.value) {
                accumulator += `&${field.name}=${field.value}`;
            }

            return accumulator;
        }, '').slice(1);

        this.setWindowLocation(categoryUrl, encodeURI(data));
    }

    protected setWindowLocation(categoryUrl: string, data: string): void {
        window.location.href = `${categoryUrl}?${data}`;
    }

    protected get formClassName(): string {
        return this.getAttribute('form-class-name');
    }

    protected get triggerClassName(): string {
        return this.getAttribute('trigger-class-name');
    }
}
