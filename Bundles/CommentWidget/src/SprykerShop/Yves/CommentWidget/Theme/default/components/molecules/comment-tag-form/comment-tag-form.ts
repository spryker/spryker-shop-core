import Component from 'ShopUi/models/component';

export default class CommentTagForm extends Component {
    protected commentTagForm: HTMLFormElement;
    protected commentTagSelectComponent: HTMLElement;

    protected readyCallback(): void {
        this.commentTagForm = <HTMLFormElement>this.querySelector(`.${this.jsName}__form`);
        this.commentTagSelectComponent = <HTMLElement>this.querySelector(`.${this.jsName}__select`);
        this.mapEvents();
    }

    protected mapEvents(): void {
        if (this.isAjaxMode) {
            return;
        }

        this.commentTagSelectComponent.addEventListener('change', () => {
            this.commentTagForm.submit();
        });
    }

    get isAjaxMode(): string {
        return this.getAttribute('ajax-mode');
    }
}
