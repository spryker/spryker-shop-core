import Component from 'ShopUi/models/component';
import { EVENT_UPDATE_DYNAMIC_MESSAGES } from 'ShopUi/components/organisms/dynamic-notification-area/dynamic-notification-area';
import { EVENT_UPDATE_CART_QUANTITY } from 'ShopUi/components/molecules/cart-counter/cart-counter';
import { error } from 'ShopUi/app/logger';

export default class AjaxAddToCart extends Component {
    protected button: HTMLButtonElement;

    protected readyCallback(): void {}

    protected init(): void {
        this.button = <HTMLButtonElement>this.getElementsByClassName(`${this.jsName}__button`)[0];
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.mapButtonClickEvent();
    }

    protected mapButtonClickEvent(): void {
        this.button.addEventListener('click', (event: Event) => this.onClick(event));
    }

    protected onClick(event: Event): void {
        event.preventDefault();

        this.sendRequest();
    }

    protected async sendRequest(): Promise<void> {
        const formData = new FormData();

        formData.append('_token', this.button.dataset.csrfToken);
        formData.append('quantity', this.button.dataset.quantity);
        formData.append('separate_product', this.button.dataset.separateProduct);
        fetch(this.button.dataset.url, { method: 'POST', body: formData })
            .then(response => response.json())
            .then(parsedResponse => {
                if (!parsedResponse.messages) {

                    return;
                }
                const dynamicNotificationCustomEvent = new CustomEvent(EVENT_UPDATE_DYNAMIC_MESSAGES, {
                    detail: parsedResponse.messages,
                });
                document.dispatchEvent(dynamicNotificationCustomEvent);

                const cartCounterCustomEvent = new CustomEvent(EVENT_UPDATE_CART_QUANTITY, {
                    detail: parsedResponse.quantity,
                });
                document.dispatchEvent(cartCounterCustomEvent);
            }).catch(e => {
                error(e);
            });
    }
}
