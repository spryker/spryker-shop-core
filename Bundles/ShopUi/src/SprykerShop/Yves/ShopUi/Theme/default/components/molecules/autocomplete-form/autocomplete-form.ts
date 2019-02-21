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

const keyCodes: {
    [key: string]: number;
} = {
    arrowUp: 38,
    arrowDown: 40,
    enter: 13
};

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
    ajaxProvider: AjaxProvider
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

    protected readyCallback(): void {
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
        this.textInput.addEventListener('keydown', (event) => this.onKeyDown(event));

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

    /**
     * Sends a request to the server and triggers the custom fetching and fetched events.
     */
    async loadSuggestions(): Promise<void> {
        this.dispatchCustomEvent(Events.FETCHING);
        this.showSuggestions();
        this.ajaxProvider.queryParams.set(this.queryString, this.inputText);

        await this.ajaxProvider.fetch();
        this.suggestionItems = Array.from(this.suggestionsContainer.querySelectorAll(this.suggestedItemSelector));
        this.lastSelectedItem = this.suggestionItems[0];
        this.mapSuggestionItemsEvents();
        this.dispatchCustomEvent(Events.FETCHED);
    }

    protected mapSuggestionItemsEvents(): void {
        const self = this;
        this.suggestionItems.forEach((item: HTMLElement) => {
            item.addEventListener('click', (e: Event) => self.onItemClick(e));
            item.addEventListener('mouseover', (e: Event) => this.onItemSelected(e));
        });
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

    protected onItemSelected(e: Event): void {
        const item = <HTMLElement>e.srcElement;
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

        switch (event.keyCode) {
            case keyCodes.arrowUp:
                event.preventDefault();
                this.onKeyDownArrowUp();
                break;
            case keyCodes.arrowDown:
                event.preventDefault();
                this.onKeyDownArrowDown();
                break;
            case keyCodes.enter:
                event.preventDefault();
                this.onKeyDownEnter();
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

    protected onKeyDownEnter(): void {
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
        return `${this.suggestedItemSelector}--selected`.substr(1);
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
     * Sets an input value.
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
     */
    get suggestedItemSelector(): string {
        return this.getAttribute('suggested-item-selector');
    }

    /**
     * Gets a time delay for the blur and input events.
     */
    get debounceDelay(): number {
        return Number(this.getAttribute('debounce-delay'));
    }

    /**
     * Gets the number of letters which, upon entering in form field, is sufficient to show, hide or load the suggestions.
     */
    get minLetters(): number {
        return Number(this.getAttribute('min-letters'));
    }
}
