import Component from 'ShopUi/models/component';
import MainPopup, { EVENT_POPUP_OPENED } from 'ShopUi/components/molecules/main-popup/main-popup';

export default class OrderAmendment extends Component {
    protected popup: MainPopup;
    protected popupContent: HTMLElement;

    protected readyCallback(): void {}

    protected init(): void {
        this.popupContent = this.querySelector(`[content-id="${this.popupContentId()}"]`) as HTMLElement;
        if (!this.popupContent) {
            return;
        }

        this.popup = this.popupContent.closest('main-popup') as MainPopup;
        this.mountEvents();
    }

    protected mountEvents(): void {
        this.popup.addEventListener(EVENT_POPUP_OPENED, () => this.onOpenPopup());
    }

    protected onOpenPopup(): void {
        this.popup.clone.querySelectorAll(this.formSelector).forEach((target: Component) => {
            target.setAttribute(this.attributeToAdd, '');
            target.init();
        });
    }

    protected get attributeToAdd(): string {
        return this.getAttribute('attribute-to-add');
    }

    protected popupContentId(): string {
        return this.getAttribute('popup-content-id');
    }

    protected get formSelector(): string {
        return this.getAttribute('form-selector');
    }
}
