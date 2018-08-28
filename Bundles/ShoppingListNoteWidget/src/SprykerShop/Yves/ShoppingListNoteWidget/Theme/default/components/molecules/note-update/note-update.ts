import Component from 'ShopUi/models/component';

export default class ShoppingListNote extends Component {
    button: HTMLElement
    noteTextFieldWrapper: HTMLElement

    protected readyCallback(): void {
        this.button = <HTMLFormElement>this.querySelector(`.${this.jsName}`);
        this.noteTextFieldWrapper = <HTMLFormElement>this.querySelector(`.${'shopping-list-item-note-wrapper'}`);
        this.mapEvents();
    }

    protected mapEvents(): void {
        if(this.button) {
            this.button.addEventListener('click', (e)=>{
                e.preventDefault();
                this.button.classList.add('is-hidden');
                this.noteTextFieldWrapper.classList.remove('is-hidden');
            })
        }
    }
}
