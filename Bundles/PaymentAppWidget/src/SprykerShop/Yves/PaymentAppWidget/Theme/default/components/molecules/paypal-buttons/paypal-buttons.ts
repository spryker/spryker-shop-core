import ScriptLoader, { EVENT_SCRIPT_LOAD } from 'ShopUi/components/molecules/script-loader/script-loader';
import { EVENT_UPDATE_DYNAMIC_MESSAGES } from 'ShopUi/components/organisms/dynamic-notification-area/dynamic-notification-area';
import Component from 'ShopUi/models/component';

declare global {
    interface Window {
        paypal?: {
            Buttons(...args: unknown[]): Window['paypal'];
            render: (element: HTMLElement) => void;
        };
    }
}

export interface PayPalButtonStyle {
    borderRadius?: number;
    color?: 'gold' | 'blue' | 'silver' | 'white' | 'black';
    disableMaxWidth?: boolean;
    height?: number;
    label?: 'paypal' | 'checkout' | 'buynow' | 'pay' | 'installment' | 'subscribe' | 'donate';
    layout?: 'vertical' | 'horizontal';
    shape?: 'rect' | 'pill' | 'sharp';
    tagline?: boolean;
}

export interface RequestData {
    paymentMethod: string;
    paymentProvider: string;
    csrfToken: string;
    csrfTokenName: string;
}

export interface ResponseData {
    content: {
        orderId: string;
        merchantId: string;
        payId: string;
    };
    messages?: string;
    csrfToken: string;
    redirectUrl?: string;
}

export interface Urls {
    preOrderUrl: string;
    successUrl: string;
    failureUrl: string;
    cancelUrl: string;
}

export default class PaypalButtons extends Component {
    protected scriptLoader: ScriptLoader;
    protected buttons: HTMLElement;

    protected requestData: RequestData;
    protected urls: Urls;
    protected csrfToken: string;

    constructor() {
        super();

        this.buttons = this.querySelector<HTMLElement>(`.${this.jsName}__buttons`);
        this.scriptLoader = this.querySelector<ScriptLoader>(`.${this.jsName}__script-loader`);
    }

    protected readyCallback(): void {}

    protected init(): void {
        this.requestData = this.requestDataParam;
        this.urls = this.urlsParam;
        this.csrfToken = this.requestData.csrfToken;

        if (this.buttons) {
            this.mapEvents();
        }
    }

    protected mapEvents(): void {
        if (window.paypal) {
            this.initPaypalButtons();

            return;
        }

        this.scriptLoader.addEventListener(EVENT_SCRIPT_LOAD, this.initPaypalButtons.bind(this), {
            once: true,
        });
    }

    protected initPaypalButtons(): void {
        window.paypal
            .Buttons({
                style: this.getButtonStyles(),
                createOrder: this.createOrder.bind(this),
                onInit: this.paypalInit.bind(this),
                onApprove: (data: Record<string, unknown>) => this.handleTransaction(data, this.urls.successUrl),
                onCancel: (data: Record<string, unknown>) => this.handleTransaction(data, this.urls.cancelUrl),
            })
            .render(this.buttons);
    }

    protected getButtonStyles(): PayPalButtonStyle {
        return {
            disableMaxWidth: true,
        };
    }

    protected async paypalInit(): Promise<void> {
        this.classList.remove(this.loadingClass);
    }

    protected async createOrder(): Promise<string> {
        this.classList.add(this.loadingClass);

        try {
            const response: ResponseData = await (
                await fetch(this.urls.preOrderUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        ...this.requestData,
                        csrfToken: this.csrfToken,
                    }),
                })
            ).json();

            this.handleErrorTransaction(response);

            this.csrfToken = response.csrfToken;

            return response.content.orderId;
        } finally {
            this.classList.remove(this.loadingClass);
        }
    }

    protected async handleTransaction(data: Record<string, unknown>, url: string): Promise<void> {
        this.classList.add(this.loadingClass);

        try {
            const response: ResponseData = await (
                await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        ...data,
                        csrfToken: this.csrfToken,
                        csrfTokenName: this.requestDataParam.csrfTokenName,
                    }),
                })
            ).json();

            this.handleErrorTransaction(response);

            if (response.redirectUrl) {
                window.location.assign(response.redirectUrl);
            }
        } finally {
            this.classList.remove(this.loadingClass);
        }
    }

    protected handleErrorTransaction(data: Error): void {
        if (!data.messages) {
            return;
        }

        document.dispatchEvent(
            new CustomEvent(EVENT_UPDATE_DYNAMIC_MESSAGES, {
                detail: data.messages,
            }),
        );
    }

    protected get loadingClass(): string {
        return this.getAttribute('loading-class');
    }

    protected get urlsParam(): Urls {
        return JSON.parse(this.getAttribute('urls') ?? '{}');
    }

    protected get requestDataParam(): RequestData {
        return JSON.parse(this.getAttribute('request-data') ?? '{}');
    }
}
