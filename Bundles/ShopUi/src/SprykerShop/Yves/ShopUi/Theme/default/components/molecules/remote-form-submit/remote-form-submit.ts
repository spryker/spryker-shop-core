import Component from 'ShopUi/models/component';

export default class ShoppingListRemoteFormSubmit extends Component {
    protected formHolder: HTMLElement;
    protected submitButton: HTMLButtonElement;

    protected readyCallback(): void {}

    protected init(): void {
        this.submitButton = <HTMLButtonElement>Array.from(this.getElementsByClassName(`${this.jsName}__submit`))[0];

        this.getFormHolder();
        this.createForm();
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.submitButton.addEventListener('click', () => this.submitTargetForm());
    }

    protected submitTargetForm(): void {
        const formId = this.submitButton.getAttribute('target-form-id');
        const form = <HTMLFormElement>document.getElementById(formId);

        form.submit();
    }

    protected getFormHolder(): void {
        if (this.formHolderClassName) {
            this.formHolder = <HTMLElement>Array.from(document.getElementsByClassName(this.formHolderClassName))[0];

            return;
        }

        this.formHolder = document.body;
    }

    protected createForm(): void {
        const formTemplate = `
            <form id="${this.formName}" class="is-hidden" name="${this.formName}" method="post" action="${this.formAction}">
                <input id="${this.tokenId}" name="${this.fieldName}" type="hidden" value="${this.formToken}">
            </form>
        `;
        this.formHolder.insertAdjacentHTML('beforeend', formTemplate);
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

    protected get tokenId(): string {
        return this.getAttribute('token-id');
    }
}
