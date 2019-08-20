import Component from 'ShopUi/models/component';

export default class ShoppingListNoteToggler extends Component {
    button: HTMLFormElement;
    noteTextFieldWrapper: HTMLElement;
    hiddenClass: string;
    noteTextarea: HTMLFormElement;

    protected readyCallback(): void {
        this.button = <HTMLFormElement>this.getElementsByClassName(`${this.jsName}__button`)[0];
        this.noteTextFieldWrapper = <HTMLFormElement>this.getElementsByClassName(`${this.jsName}__wrapper`)[0];
        this.noteTextarea = <HTMLFormElement>this.getElementsByClassName(`${this.jsName}__note-textarea`)[0];
        this.hiddenClass = 'is-hidden';
        this.mapEvents();
    }

    protected mapEvents(): void {
        if (this.button) {
            this.button.addEventListener('click', (event: Event) => this.onClick(event));
        }
    }

    private onClick(event: Event): void {
        event.preventDefault();
        this.toggleNote();
        this.focusTextarea();
    }

    private toggleNote(): void {
        this.button.classList.add(this.hiddenClass);
        this.noteTextFieldWrapper.classList.remove(this.hiddenClass);
    }

    private focusTextarea(): void {
        this.noteTextarea.focus();
    }
}
