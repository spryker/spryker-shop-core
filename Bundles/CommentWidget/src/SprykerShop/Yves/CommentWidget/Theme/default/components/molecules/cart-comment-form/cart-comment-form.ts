import Component from 'ShopUi/models/component';

export default class CartCommentForm extends Component {
    protected cartCommentForm: HTMLFormElement;
    protected editButtonForm: HTMLButtonElement;
    protected editButtonFormAction: string;
    protected removeButtonForm: HTMLButtonElement;
    protected removeButtonFormAction: string;

    protected readyCallback(): void {
        this.cartCommentForm = <HTMLFormElement>this.getElementsByClassName(`${this.jsName}__element`)[0];
        this.editButtonForm = <HTMLButtonElement>this.getElementsByClassName(`${this.jsName}__edit-button`)[0];
        this.editButtonFormAction = this.editButtonForm.getAttribute('action');
        this.removeButtonForm = <HTMLButtonElement>this.getElementsByClassName(`${this.jsName}__remove-button`)[0];
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.editButtonForm.addEventListener('click', (event: Event) => this.onEditButtonFormClick(event));

        if (this.removeButtonForm) {
            this.removeButtonFormAction = this.removeButtonForm.getAttribute('action');
            this.removeButtonForm.addEventListener('click', (event: Event) => this.onRemoveButtonFormClick(event));
        }
    }

    protected onEditButtonFormClick(event: Event) {
        event.preventDefault();
        this.cartCommentForm.setAttribute('action', this.editButtonFormAction);
        this.cartCommentForm.submit();
    }

    protected onRemoveButtonFormClick(event: Event) {
        event.preventDefault();
        this.cartCommentForm.setAttribute('action', this.removeButtonFormAction);
        this.cartCommentForm.submit();
    }
}
