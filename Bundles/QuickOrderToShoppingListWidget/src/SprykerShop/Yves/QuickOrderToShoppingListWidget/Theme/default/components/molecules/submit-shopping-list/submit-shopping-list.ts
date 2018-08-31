import Component from 'ShopUi/models/component';

export default class SubmitShoppingList extends Component {
    button: HTMLButtonElement
    quickOrderForm: HTMLFormElement

    protected readyCallback(): void {
        this.button = <HTMLButtonElement>this.querySelector(`.${this.jsName}`);
        this.quickOrderForm = <HTMLFormElement>document.querySelector(`.${'js-quick-order-form__form'}`);
        this.mapEvents();
    }

    protected mapEvents(): void {
        if(this.button) {
            this.button.addEventListener('click', (e)=>{
                e.preventDefault();
                this.quickOrderForm.action = 'shopping-list/create-from-quick-order';
                this.quickOrderForm.submit();
            })
        }
    }
}
