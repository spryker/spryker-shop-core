import Component from '../../../models/component';
import { EVENT_UPDATE_DYNAMIC_MESSAGES } from 'ShopUi/components/organisms/dynamic-notification-area/dynamic-notification-area';

/**
 * @event updateCartQuantity An event emitted when need to update cart quantity.
 */
export const EVENT_UPDATE_CART_QUANTITY = 'updateCartQuantity';

export default class CartCounter extends Component {
    protected quantity: HTMLElement;

    protected readyCallback(): void {}

    protected init(): void {
        this.quantity = <HTMLElement>this.getElementsByClassName(`${this.jsName}__quantity`)[0];
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.mapTriggerCustomUpdateQuantityEvent();
    }

    protected mapTriggerCustomUpdateQuantityEvent(): void {
        document.addEventListener(EVENT_UPDATE_CART_QUANTITY,
            (event: CustomEvent) => this.updateQuantity(Number(event.detail)));
    }

    protected updateQuantity(quantity: number): void {
        if (!quantity) {
            return;
        }
        this.quantity.textContent = String(quantity);
        this.quantity.classList.toggle(this.hiddenQuantityClassName, !quantity);
    }

    protected get hiddenQuantityClassName(): string {
        return this.getAttribute('hidden-quantity-class-name');
    }
}
