import Component from '../../../models/component';
import AjaxProvider from '../../../components/molecules/ajax-provider/ajax-provider';
import debounce from 'lodash-es/debounce';

export enum Events {
    FETCHING = 'fetching',
    FETCHED = 'fetched',
    CHANGE = 'change',
    SET = 'set',
    UNSET = 'unset'
}

export default class AutocompleteForm extends Component {
    ajaxProvider: AjaxProvider
    textInput: HTMLInputElement;
    valueInput: HTMLInputElement;
    suggestionsContainer: HTMLElement;
    cleanButton: HTMLButtonElement;

    readyCallback(): void {
        this.ajaxProvider = <AjaxProvider>this.querySelector(`.${this.jsName}__provider`);
        this.textInput = <HTMLInputElement>this.querySelector(`.${this.jsName}__text-input`);
        this.valueInput = <HTMLInputElement>this.querySelector(`.${this.jsName}__value-input`);
        this.suggestionsContainer = <HTMLElement>this.querySelector(`.${this.jsName}__suggestions`);
        this.cleanButton = <HTMLButtonElement>this.querySelector(`.${this.jsName}__clean-button`);
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.textInput.addEventListener('input', debounce(() => this.onInput(), this.debounceDelay));
        this.textInput.addEventListener('blur', debounce(() => this.onBlur(), this.debounceDelay));
        this.textInput.addEventListener('focus', () => this.onFocus());

        if (!this.cleanButton) {
            return;
        }

        this.cleanButton.addEventListener('click', () => this.onCleanButtonClick());
    }

    protected onCleanButtonClick(): void {
        this.clean();
        this.dispatchCustomEvent(Events.UNSET);
    }

    protected onBlur(): void {
        this.hideSuggestions();
    }

    protected onFocus(): void {
        if (this.inputValue.length < this.minLetters) {
            return;
        }

        this.showSuggestions();
    }

    protected onInput(): void {
        this.dispatchCustomEvent(Events.CHANGE);

        if (this.inputText.length >= this.minLetters) {
            this.loadSuggestions();
            return;
        }

        this.hideSuggestions();

        if (!!this.inputValue) {
            this.inputValue = '';
            this.dispatchCustomEvent(Events.UNSET);
        }
    }

    protected showSuggestions(): void {
        this.suggestionsContainer.classList.remove('is-hidden');
    }

    protected hideSuggestions(): void {
        this.suggestionsContainer.classList.add('is-hidden');
    }

    async loadSuggestions(): Promise<void> {
        this.dispatchCustomEvent(Events.FETCHING);

        this.showSuggestions();
        this.ajaxProvider.queryParams.set(this.queryString, this.inputText);
        await this.ajaxProvider.fetch();
        this.mapItemEvents();

        this.dispatchCustomEvent(Events.FETCHED);
    }

    protected mapItemEvents(): void {
        const self = this;
        const items = Array.from(this.suggestionsContainer.querySelectorAll(this.suggestedItemSelector));
        items.forEach((item: HTMLElement) => item.addEventListener('click', (e: Event) => self.onItemClick(e)));
    }

    protected onItemClick(e: Event): void {
        const textTargetElement = <HTMLElement>e.srcElement;
        const valueTargetElement = <HTMLElement>e.target;

        this.inputText = textTargetElement.textContent.trim();
        this.inputValue = valueTargetElement.getAttribute(this.valueAttributeName);

        this.dispatchCustomEvent(Events.SET, {
            text: this.inputText,
            value: this.inputValue
        });
    }

    clean(): void {
        this.inputText = '';
        this.inputValue = '';
    }

    get inputText(): string {
        return this.textInput.value.trim();
    }

    set inputText(value: string) {
        this.textInput.value = value;
    }

    get inputValue(): string {
        return this.valueInput.value;
    }

    set inputValue(value: string) {
        this.valueInput.value = value;
    }

    get queryString(): string {
        return this.getAttribute('query-string');
    }

    get valueAttributeName(): string {
        return this.getAttribute('value-attribute-name');
    }

    get suggestedItemSelector(): string {
        return this.getAttribute('suggested-item-selector');
    }

    get debounceDelay(): number {
        return Number(this.getAttribute('debounce-delay'));
    }

    get minLetters(): number {
        return Number(this.getAttribute('min-letters'));
    }
}
