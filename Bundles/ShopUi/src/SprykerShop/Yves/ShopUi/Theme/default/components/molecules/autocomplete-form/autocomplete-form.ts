/* eslint-disable max-lines */
import Component from '../../../models/component';
import AjaxProvider from '../../../components/molecules/ajax-provider/ajax-provider';
import debounce from 'lodash-es/debounce';

export enum Events {
    FETCHING = 'fetching',
    FETCHED = 'fetched',
    CHANGE = 'change',
    SET = 'set',
    UNSET = 'unset',
}

/**
 * @event fetching An event which is triggered when an ajax request is sent to the server.
 * @event fetched An event which is triggered when an ajax request is closed.
 * @event change An event which is triggered when the search field is changed.
 * @event set An event which is triggered when the element of an autocomplete suggestion is selected.
 * @event unset An event which is triggered when the element of an autocomplete suggestion is removed.
 */
export default class AutocompleteForm extends Component {
    /**
     * Performs the Ajax operations.
     */
    ajaxProvider: AjaxProvider;

    /**
     * The text input element.
     */
    textInput: HTMLInputElement;

    /**
     * The value input element.
     */
    valueInput: HTMLInputElement;

    /**
     * The contains of the suggestions.
     */
    suggestionsContainer: HTMLElement;

    /**
     * Collection of the suggestions items.
     */
    suggestionItems: HTMLElement[];

    /**
     * The trigger of the form clearing.
     */
    cleanButton: HTMLButtonElement;

    /**
     * The last selected saggestion item.
     */
    lastSelectedItem: HTMLElement;
    protected injectorsExtraQueryValueList: HTMLSelectElement[] | HTMLInputElement[];
    protected extraQueryValues = new Map();

    protected readyCallback(): void {
        this.ajaxProvider = <AjaxProvider>this.getElementsByClassName(`${this.jsName}__provider`)[0];
        this.textInput = <HTMLInputElement>this.getElementsByClassName(`${this.jsName}__text-input`)[0];
        this.valueInput = <HTMLInputElement>this.getElementsByClassName(`${this.jsName}__value-input`)[0];
        this.suggestionsContainer = <HTMLElement>this.getElementsByClassName(`${this.jsName}__suggestions`)[0];
        this.cleanButton = <HTMLButtonElement>this.getElementsByClassName(`${this.jsName}__clean-button`)[0];

        if (this.injectorsExtraQueryValueClassName) {
            this.injectorsExtraQueryValueList = <HTMLSelectElement[] | HTMLInputElement[]>(
                Array.from(document.getElementsByClassName(this.injectorsExtraQueryValueClassName))
            );
        }

        if (this.autoInitEnabled) {
            this.autoLoadInit();
        }

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.textInput.addEventListener(
            'input',
            debounce(() => this.onInput(), this.debounceDelay),
        );
        this.textInput.addEventListener(
            'blur',
            debounce(() => this.onBlur(), this.debounceDelay),
        );
        this.textInput.addEventListener('focus', () => this.onFocus());
        this.textInput.addEventListener('keydown', (event) => this.onKeyDown(event));
        if (this.cleanButton) {
            this.cleanButton.addEventListener('click', () => this.onCleanButtonClick());
        }
    }

    protected autoLoadInit(): void {
        this.textInput.focus();
        this.loadSuggestions();
    }

    protected onCleanButtonClick(): void {
        this.clean();
        this.dispatchCustomEvent(Events.UNSET);
    }

    protected onBlur(): void {
        this.hideSuggestions();
    }

    protected onFocus(): void {
        if (this.inputText.length < this.minLetters) {
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

    protected setQueryParams(): void {
        this.extraQueryValues.clear();
        this.ajaxProvider.queryParams.clear();
        this.ajaxProvider.queryParams.set(this.queryString, this.inputText);

        if (!this.injectorsExtraQueryValueList || !this.injectorsExtraQueryValueList.length) {
            return;
        }

        this.injectorsExtraQueryValueList.forEach((item) => {
            if (!item.name || !item.value) {
                return;
            }

            this.ajaxProvider.queryParams.set(item.name, item.value);
            this.extraQueryValues.set(item.name, item.value);
        });
    }

    /**
     * Sends a request to the server and triggers the custom fetching and fetched events.
     */
    async loadSuggestions(): Promise<void> {
        this.dispatchCustomEvent(Events.FETCHING);
        this.showSuggestions();
        this.setQueryParams();

        await this.ajaxProvider.fetch();
        this.suggestionItems = <HTMLElement[]>Array.from(
            this.suggestionsContainer.getElementsByClassName(this.suggestedItemClassName) ||
                // eslint-disable-next-line deprecation/deprecation
                this.suggestionsContainer.querySelectorAll(this.suggestedItemSelector),
        );
        this.lastSelectedItem = this.suggestionItems[0];
        this.mapSuggestionItemsEvents();
        this.dispatchCustomEvent(Events.FETCHED);
    }

    protected mapSuggestionItemsEvents(): void {
        this.suggestionItems.forEach((item: HTMLElement) => {
            item.addEventListener('click', () => this.onItemClick(item));
            item.addEventListener('mouseover', () => this.onItemSelected(item));
        });
    }

    protected onItemClick(item: HTMLElement): void {
        this.inputText = item.textContent.trim();
        this.inputValue = item.getAttribute(this.valueAttributeName);
        this.dispatchCustomEvent(Events.SET, {
            text: this.inputText,
            value: this.inputValue,
            extraValues: this.extraQueryValues,
        });
    }

    protected onItemSelected(item: HTMLElement): void {
        this.changeSelectedItem(item);
    }

    protected changeSelectedItem(item: HTMLElement): void {
        this.lastSelectedItem.classList.remove(this.selectedInputClass);
        item.classList.add(this.selectedInputClass);
        this.lastSelectedItem = item;
    }

    protected onKeyDown(event: KeyboardEvent): void {
        if (!this.suggestionItems && this.inputText.length < this.minLetters) {
            return;
        }
        switch (event.key) {
            case 'ArrowUp':
                this.onKeyDownArrowUp();
                break;
            case 'ArrowDown':
                this.onKeyDownArrowDown();
                break;
            case 'Enter':
                this.onKeyDownEnter(event);
                break;
        }
    }

    protected onKeyDownArrowUp(): void {
        const lastSelectedItemIndex = this.suggestionItems.indexOf(this.lastSelectedItem);
        const elementIndex = lastSelectedItemIndex - 1;
        const lastSuggestionItemIndex = this.suggestionItems.length - 1;
        const item = this.suggestionItems[elementIndex < 0 ? lastSuggestionItemIndex : elementIndex];
        this.changeSelectedItem(item);
    }

    protected onKeyDownArrowDown(): void {
        const lastSelectedItemIndex = this.suggestionItems.indexOf(this.lastSelectedItem);
        const elementIndex = lastSelectedItemIndex + 1;
        const lastSuggestionItemIndex = this.suggestionItems.length - 1;
        const item = this.suggestionItems[elementIndex > lastSuggestionItemIndex ? 0 : elementIndex];
        this.changeSelectedItem(item);
    }

    protected onKeyDownEnter(event: KeyboardEvent): void {
        event.preventDefault();
        this.lastSelectedItem.click();
    }

    /**
     * Clears the input value and the typed text.
     */
    clean(): void {
        this.inputText = '';
        this.inputValue = '';
    }

    /**
     * Gets the css query selector of the selected suggestion items.
     */
    get selectedInputClass(): string {
        // eslint-disable-next-line deprecation/deprecation
        return `${this.suggestedItemClassName}--selected` || `${this.suggestedItemSelector}--selected`.substr(1);
    }

    /**
     * Gets the typed text from the form field.
     */
    get inputText(): string {
        return this.textInput.value.trim();
    }

    /**
     * Sets an input text.
     * @param value A typed text in the input field.
     */
    set inputText(value: string) {
        this.textInput.value = value;
    }

    /**
     * Gets the value attribute from the input form field.
     */
    get inputValue(): string {
        return this.valueInput.value;
    }

    /**
     * Sets the input value.
     */
    set inputValue(value: string) {
        this.valueInput.value = value;
    }

    /**
     * Gets the css query selector for the ajaxProvider configuration.
     */
    get queryString(): string {
        return this.getAttribute('query-string');
    }

    /**
     * Gets the value attribute name for the input element configuration.
     */
    get valueAttributeName(): string {
        return this.getAttribute('value-attribute-name');
    }

    /**
     * Gets the css query selector of the suggestion items.
     *
     * @deprecated Use suggestedItemClassName() instead.
     */
    get suggestedItemSelector(): string {
        return this.getAttribute('suggested-item-selector');
    }

    protected get suggestedItemClassName(): string {
        return this.getAttribute('suggested-item-class-name');
    }

    protected get injectorsExtraQueryValueClassName(): string {
        return this.getAttribute('injectors-extra-query-value-class-name');
    }

    /**
     * Gets a time delay for the blur and input events.
     */
    get debounceDelay(): number {
        return Number(this.getAttribute('debounce-delay'));
    }

    /**
     * Gets the number of letters which, upon entering in form field, is sufficient to show, hide or load the
     * suggestions.
     */
    get minLetters(): number {
        return Number(this.getAttribute('min-letters'));
    }

    /**
     * Gets if the auto load of suggestions is enabled.
     */
    get autoInitEnabled(): boolean {
        return this.hasAttribute('auto-init');
    }
}
