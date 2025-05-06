import Component from 'ShopUi/models/component';

export default class RemoteFormSubmit extends Component {
    protected formHolder: HTMLElement;
    protected fieldsContainer: HTMLElement;
    protected submitButton: HTMLButtonElement;

    protected readyCallback(): void {}

    protected init(): void {
        if (!this.initialMount) {
            return;
        }
        this.fieldsContainer = <HTMLElement>this.getElementsByClassName(`${this.jsName}__container`)[0];
        this.submitButton = <HTMLButtonElement>this.getElementsByClassName(`${this.jsName}__submit`)[0];

        this.getFormHolder();
        this.createForm();
        this.removeFieldsContainer();
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.mapSubmitButtonClickEvent();
    }

    protected mapSubmitButtonClickEvent(): void {
        this.submitButton.addEventListener('click', () => this.submitTargetForm());
    }

    protected submitTargetForm(): void {
        const form = <HTMLFormElement>document.getElementById(this.formName);

        if (this.submitButton.dataset.formAction) {
            form.action = this.submitButton.dataset.formAction;
        }

        form.submit();
    }

    protected getFormHolder(): void {
        if (this.formHolderClassName) {
            this.formHolder = <HTMLElement>document.getElementsByClassName(this.formHolderClassName)[0];

            return;
        }

        this.formHolder = document.body;
    }

    protected createForm(): void {
        if (document.getElementById(this.formName)?.tagName === 'form') {
            return;
        }

        const formTemplate = `
            <form id="${this.formName}" class="is-hidden" name="${this.formName}" method="post" action="${this.formAction}">
                ${this.fieldsContainer.innerHTML}
            </form>
        `;
        this.formHolder.insertAdjacentHTML('beforeend', formTemplate);
    }

    protected removeFieldsContainer(): void {
        this.fieldsContainer.remove();
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

    protected get initialMount(): boolean {
        return this.hasAttribute('initial-mount');
    }
}
