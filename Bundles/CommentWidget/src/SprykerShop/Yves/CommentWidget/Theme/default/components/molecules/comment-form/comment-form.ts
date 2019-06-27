import Component from 'ShopUi/models/component';

export default class CommentForm extends Component {
    protected commentForm: HTMLFormElement;
    protected editButtonForm: HTMLButtonElement;
    protected removeButtonForm: HTMLButtonElement;

    protected readyCallback(): void {
        this.commentForm = <HTMLFormElement>this.querySelector(`.${this.jsName}__form`);
        this.editButtonForm = <HTMLButtonElement>this.querySelector(`.${this.jsName}__submit-button`);
        this.removeButtonForm = <HTMLButtonElement>this.querySelector(`.${this.jsName}__remove-button`);
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.editButtonForm.addEventListener('click', (event: Event) => {
            this.onButtonFormClick(event, this.editFormActionAttribute);
        });

        if (this.removeButtonForm) {
            this.removeButtonForm.addEventListener('click', (event: Event) => {
                this.onButtonFormClick(event, this.removeFormActionAttribute);
            });
        }
    }

    protected onButtonFormClick(event: Event, action: string): void {
        event.preventDefault();
        this.commentForm.setAttribute('action', action);
        this.commentForm.submit();
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
