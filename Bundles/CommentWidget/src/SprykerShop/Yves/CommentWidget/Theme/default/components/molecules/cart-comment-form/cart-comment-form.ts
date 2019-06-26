import Component from 'ShopUi/models/component';

export default class CartCommentForm extends Component {
    protected cartCommentForm: HTMLFormElement;
    protected editButtonForm: HTMLButtonElement;
    protected removeButtonForm: HTMLButtonElement;

    protected readyCallback(): void {
        this.cartCommentForm = <HTMLFormElement>this.querySelector(`.${this.jsName}__element`);
        this.editButtonForm = <HTMLButtonElement>this.querySelector(`.${this.jsName}__edit-button`);
        this.removeButtonForm = <HTMLButtonElement>this.querySelector(`.${this.jsName}__remove-button`);
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.editButtonForm.addEventListener('click', (event: Event) => this.onButtonFormClick(event, this.editFormActionAttribute));

        if (this.removeButtonForm) {
            this.removeButtonForm.addEventListener('click', (event: Event) => this.onButtonFormClick(event, this.removeFormActionAttribute));
        }
    }

    protected onButtonFormClick(event: Event, action: string): void {
        event.preventDefault();
        this.cartCommentForm.setAttribute('action', action);
        this.cartCommentForm.submit();
    }

    /**
     * Gets an attribute name for form element.
     */
    get editFormActionAttribute(): string {
        return this.editButtonForm.getAttribute('action');
    }

    /**
     * Gets an attribute name for form element.
     */
    get removeFormActionAttribute(): string {
        return this.removeButtonForm.getAttribute('action');
    }
}
