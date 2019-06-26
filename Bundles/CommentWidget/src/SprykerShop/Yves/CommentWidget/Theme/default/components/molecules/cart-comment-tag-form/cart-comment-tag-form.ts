import Component from 'ShopUi/models/component';

export default class CartCommentTagForm extends Component {
    protected cartCommentTagForm: HTMLFormElement;
    protected cartCommentTagSelectComponent: HTMLElement;

    protected readyCallback(): void {
        this.cartCommentTagForm = <HTMLFormElement>this.querySelector(`.${this.jsName}__element`);
        this.cartCommentTagSelectComponent = <HTMLElement>this.querySelector(`.${this.jsName}__select`);
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.cartCommentTagSelectComponent.addEventListener('change', () => this.cartCommentTagForm.submit());
    }
}
