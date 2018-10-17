import Component from 'ShopUi/models/component';

export default class ShoppingListNoteToggler extends Component {
    button: HTMLFormElement;
    noteTextFieldWrapper: HTMLElement;
    hiddenClass: string;
    noteTextarea: HTMLFormElement;

    protected readyCallback(): void {
        this.button = <HTMLFormElement>this.querySelector(`.${this.jsName}__button`);
        this.noteTextFieldWrapper = <HTMLFormElement>this.querySelector(`.${this.jsName}__wrapper`);
        this.noteTextarea = <HTMLFormElement>this.querySelector(`.${this.jsName}__note-textarea`);
        this.hiddenClass = 'is-hidden';
        this.mapEvents();
    }

    protected mapEvents(): void {
        if(this.button) {
            this.button.addEventListener('click', (event: Event) => this.onClick(event))
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
