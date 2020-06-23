import Component from 'ShopUi/models/component';

export default class ShoppingListRemoteFormSubmit extends Component {
    protected formHolder: HTMLElement;
    protected submitButton: HTMLButtonElement;

    protected readyCallback(): void {}

    protected init(): void {
        this.submitButton = <HTMLButtonElement>Array.from(this.getElementsByClassName(`${this.jsName}__submit`))[0];
        this.formHolder = <HTMLElement>Array.from(document.getElementsByClassName(this.formHolderClassName))[0];

        this.createFormTemplate();
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.submitButton.addEventListener('click', (event: Event) => this.onClick(event));
    }

    protected onClick(event: Event): void {
        this.submitTargetForm(event);
    }

    protected submitTargetForm(event: Event) {
        const target = <HTMLButtonElement>event.currentTarget;
        const formId = target.getAttribute('target-form-id');
        const form = <HTMLFormElement>document.getElementById(formId);

        form.submit();
    }

    protected createFormTemplate(): void {
        const container = document.createElement('div');
        const formTemplate = `
            <form id="${this.formName}" name="${this.formName}" method="post" action="${this.formAction}">
                <input id="shopping_list_remove_item_form__token[${this.formName}]" name="${this.fieldName}" class="input input--expand" type="hidden" placeholder="" value="${this.formToken}">
            </form>
        `;
        container.innerHTML = formTemplate;
        this.formHolder.appendChild(container);
    }

    protected get formHolderClassName(): string {
        return this.getAttribute('form-holder-class-name');
    }

    protected get formName(): string {
        return this.getAttribute('form-name');
    }

    protected get formAction(): string {
        return this.getAttribute('form-action');
    }

    protected get formToken(): string {
        return this.getAttribute('form-token');
    }

    protected get fieldName(): string {
        return this.getAttribute('field-name');
    }
}
