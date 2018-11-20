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
    suggestionItems: HTMLElement[];
    cleanButton: HTMLButtonElement;
    lastSelectedItem: HTMLElement;
    keyCodes: {
        [key: string]: number;
    };

    protected readyCallback(): void {
        this.ajaxProvider = <AjaxProvider>this.querySelector(`.${this.jsName}__provider`);
        this.textInput = <HTMLInputElement>this.querySelector(`.${this.jsName}__text-input`);
        this.valueInput = <HTMLInputElement>this.querySelector(`.${this.jsName}__value-input`);
        this.suggestionsContainer = <HTMLElement>this.querySelector(`.${this.jsName}__suggestions`);
        this.cleanButton = <HTMLButtonElement>this.querySelector(`.${this.jsName}__clean-button`);
        this.keyCodes = {
            arrowUp: 38,
            arrowDown: 40,
            tab: 9,
        };
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.textInput.addEventListener('input', debounce(() => this.onInput(), this.debounceDelay));
        this.textInput.addEventListener('blur', debounce(() => this.onBlur(), this.debounceDelay));
        this.textInput.addEventListener('focus', () => this.onFocus());
        this.textInput.addEventListener('keydown', this.onKeyDown.bind(this));

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
            item.addEventListener('mouseover', () => this.itemSelectHandler(item));
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

    protected itemSelectHandler(item): void {
        this.lastSelectedItem.classList.remove(this.selectedInputClass);
        item.classList.add(this.selectedInputClass);
        this.lastSelectedItem = item;
    }

    protected onKeyDown(event: KeyboardEvent): void {
        if (this.suggestionItems && this.suggestionItems.length) {
            let elementIndex = this.suggestionItems.indexOf(this.lastSelectedItem),
                lastSuggestionItem = this.suggestionItems.length - 1,
                item;
            switch (event.keyCode) {
                case this.keyCodes.arrowUp:
                    event.preventDefault();
                    elementIndex--;
                    item = this.suggestionItems[elementIndex < 0 ? lastSuggestionItem : elementIndex];
                    this.itemSelectHandler(item);
                    break;
                case this.keyCodes.arrowDown:
                    event.preventDefault();
                    elementIndex++;
                    item = this.suggestionItems[elementIndex > lastSuggestionItem ? 0 : elementIndex];
                    this.itemSelectHandler(item);
                    break;
                case this.keyCodes.tab:
                    event.preventDefault();
                    this.lastSelectedItem.click();
                    break;
            }
        }
    }

    clean(): void {
        this.inputText = '';
        this.inputValue = '';
    }

    get selectedInputClass(): string {
        return `${this.suggestedItemSelector}--selected`.substr(1);
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
