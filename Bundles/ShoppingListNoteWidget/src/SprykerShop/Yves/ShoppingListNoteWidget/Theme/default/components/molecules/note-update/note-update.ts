import Component from 'ShopUi/models/component';

export default class ShoppingListNote extends Component {
    button: HTMLFormElement
    noteTextFieldWrapper: HTMLElement
    hiddenClass: string

    protected readyCallback(): void {
        this.button = <HTMLFormElement>this.querySelector(`.${this.jsName}`);
        this.noteTextFieldWrapper = <HTMLFormElement>this.querySelector(`.${this.jsName}__wrapper`);
        this.hiddenClass = 'is-hidden';
        this.mapEvents();
    }

    protected mapEvents(): void {
        if(this.button) {
            this.button.addEventListener('click', (e)=>{
                e.preventDefault();
                this.button.classList.add(this.hiddenClass);
                this.noteTextFieldWrapper.classList.remove(this.hiddenClass);
            })
        }
    }
}
