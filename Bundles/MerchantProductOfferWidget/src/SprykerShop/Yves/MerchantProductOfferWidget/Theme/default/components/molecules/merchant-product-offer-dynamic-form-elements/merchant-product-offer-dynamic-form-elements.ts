import Component from 'ShopUi/models/component';
import AutocompleteForm, { Events } from 'ShopUi/components/molecules/autocomplete-form/autocomplete-form';
import AjaxProvider from 'ShopUi/components/molecules/ajax-provider/ajax-provider';

export default class MerchantProductOfferDynamicFormElements extends Component {
    protected autocompleteForm: AutocompleteForm;
    protected ajaxProvider: AjaxProvider;
    protected submitButton: HTMLButtonElement;

    protected readyCallback(): void {}

    protected init(): void {
        this.autocompleteForm = <AutocompleteForm>document.getElementsByClassName(this.autocompleteFormClassName)[0];
        this.ajaxProvider = <AjaxProvider>this.getElementsByClassName(`${this.jsName}__provider`)[0];
        if (this.submitButtonClassName) {
            this.submitButton = <HTMLButtonElement>document.getElementsByClassName(this.submitButtonClassName)[0];
        }

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.mapAutocompleteFormSetEvent();
    }

    protected mapAutocompleteFormSetEvent(): void {
        this.autocompleteForm.addEventListener(Events.SET, (event: CustomEvent) => this.onAutocompleteSet(event));
    }

    protected onAutocompleteSet(event: CustomEvent): void {
        this.sendRequest(event);
    }

    protected toggleSubmitButton(isDisabled = true): void {
        if (this.submitButton) {
            this.submitButton.disabled = isDisabled;
        }
    }

    protected async sendRequest(event: CustomEvent): Promise<void> {
        this.toggleSubmitButton();
        this.ajaxProvider.queryParams.set(this.queryString, event.detail.value);
        await this.ajaxProvider.fetch();
        this.toggleSubmitButton(false);
    }

    protected get autocompleteFormClassName(): string {
        return this.getAttribute('autocomplete-form-class-name');
    }

    protected get queryString(): string {
        return this.getAttribute('query-string');
    }

    protected get submitButtonClassName(): string {
        return this.getAttribute('submit-button-class-name');
    }
}
