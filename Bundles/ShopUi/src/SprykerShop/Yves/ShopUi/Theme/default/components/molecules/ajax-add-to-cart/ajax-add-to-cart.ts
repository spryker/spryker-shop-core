import Component from '../../../models/component';

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
        fetch(this.button.dataset.url, { method: 'POST', body: formData })
            .then(response => response.json())
            .then(parsedResponse => {
                if (!parsedResponse.messages) {

                    return;
                }

                const successCode = 200;
                if (parsedResponse.code !== successCode) {

                    return;
                }
            }).catch(error => {
            console.error(error);
        });
    }
}
