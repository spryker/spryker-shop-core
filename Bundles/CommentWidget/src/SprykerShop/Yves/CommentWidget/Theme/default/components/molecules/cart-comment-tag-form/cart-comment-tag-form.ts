import Component from 'ShopUi/models/component';

export default class CartCommentTagForm extends Component {
    protected cartCommentTagForm: HTMLFormElement;
    protected cartCommentTagSelectComponent: HTMLElement;

    protected readyCallback(): void {
        this.cartCommentTagForm = <HTMLFormElement>this.getElementsByClassName(`${this.jsName}__element`)[0];
        this.cartCommentTagSelectComponent = <HTMLElement>this.getElementsByClassName(`${this.jsName}__select`)[0];
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.cartCommentTagSelectComponent.addEventListener('change', () => this.cartCommentTagForm.submit());
    }
}
