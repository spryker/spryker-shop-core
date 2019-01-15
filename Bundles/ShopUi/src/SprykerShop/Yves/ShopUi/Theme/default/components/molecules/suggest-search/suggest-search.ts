import Component from '../../../models/component';
import AjaxProvider from '../../../components/molecules/ajax-provider/ajax-provider';
import debounce from 'lodash-es/debounce'
import throttle from 'lodash-es/throttle'

interface keyCodes {
    [keyCode: number]: string;
}

export default class SuggestSearch extends Component {
    readonly keyboardCodes: keyCodes

    /**
     * The search input element.
     */
    searchInput: HTMLInputElement
    /**
     * The hint input element.
     */
    hintInput: HTMLInputElement
    /**
     * The container of the suggestions items.
     */
    suggestionsContainer: HTMLElement
    /**
     * Performs the Ajax operations.
     */
    ajaxProvider: AjaxProvider
    /**
     * The current value of the search input element.
     */
    currentSearchValue: string
    /**
     * A content of the hint input element.
     */
    hint: string
    /**
     * The list of the search suggestions.
     */
    navigation: HTMLElement[]
    /**
     * The index of the active suggestion item.
     */
    activeItemIndex: number
    /**
     * The class name of the active suggestion item.
     */
    navigationActiveClass: string


    constructor() {
        super();

        this.keyboardCodes = {
            9: 'tab',
            13: 'enter',
            37: 'arrowLeft',
            38: 'arrowUp',
            39: 'arrowRight',
            40: 'arrowDown'
        };
        this.activeItemIndex = 0;
    }

    protected readyCallback(): void {
        this.ajaxProvider = <AjaxProvider> this.querySelector(`.${this.jsName}__ajax-provider`);
        this.suggestionsContainer = <HTMLElement> this.querySelector(`.${this.jsName}__container`);
        this.searchInput = <HTMLInputElement> document.querySelector(this.searchInputSelector);
        this.navigationActiveClass = `${this.name}__item--active`;
        this.createHintInput();
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.searchInput.addEventListener('keyup', debounce((event: Event) => this.onInputKeyUp(event), this.debounceDelay));
        this.searchInput.addEventListener('keydown', throttle((event: Event) => this.onInputKeyDown(<KeyboardEvent> event), this.throttleDelay));
        this.searchInput.addEventListener('blur', debounce((event: Event) => this.onInputFocusOut(event), this.debounceDelay));
        this.searchInput.addEventListener('focus', (event: Event) => this.onInputFocusIn(event));
        this.searchInput.addEventListener('click', (event: Event) => this.onInputClick(event));
    }

    protected async onInputKeyUp(event: Event): Promise<void> {
        const suggestQuery = this.getSearchValue();

        if (suggestQuery != this.currentSearchValue && suggestQuery.length >= this.lettersTrashold) {
            this.saveCurrentSearchValue(suggestQuery);

            await this.getSuggestions();
        }

        this.saveCurrentSearchValue(suggestQuery);

        if (suggestQuery.length < this.lettersTrashold) {
            this.setHintValue('');
            this.hideSugestions();
        }
    }

    protected onInputKeyDown(event: KeyboardEvent): void {
        var keyCode = event.keyCode;

        switch (this.keyboardCodes[keyCode]) {
            case 'enter': this.onEnter(event); break;
            case 'tab': this.onTab(event); break;
            case 'arrowUp': this.onArrowUp(event); break;
            case 'arrowDown': this.onArrowDown(event); break;
            case 'arrowLeft': this.onArrowLeft(event); break;
            case 'arrowRight': this.onArrowRight(event); break;
        }
    }

    protected onInputClick(event: Event): void {
        this.activeItemIndex = 0;
        if (this.isNavigationExist()) {
            this.updateNavigation();
            this.showSugestions();
        }
    }

    protected onTab(event: KeyboardEvent): boolean {
        this.searchInput.value = this.hint;
        event.preventDefault();
        return false;
    }

    protected onArrowUp(event: KeyboardEvent) {
        this.activeItemIndex = this.activeItemIndex > 0 ? this.activeItemIndex - 1 : 0;
        this.updateNavigation();
    }

    protected onArrowDown(event: KeyboardEvent) {
        this.activeItemIndex = this.activeItemIndex < this.navigation.length ? this.activeItemIndex + 1 : 0;
        this.updateNavigation();
    }

    protected onArrowLeft(event: KeyboardEvent) {
        this.activeItemIndex = 1;
        this.updateNavigation();
    }

    protected onArrowRight(event: KeyboardEvent): void {
        this.activeItemIndex = this.getFirstProductNavigationIndex() + 1;
        this.updateNavigation();
    }

    protected onEnter(event: KeyboardEvent): void {
        const activeItem = this.getActiveNavigationItem();
        if (activeItem) {
            this.getActiveNavigationItem().click();
            event.preventDefault();
        }
    }

    protected onInputFocusIn(event: Event): void {
        this.activeItemIndex = 0;
    }

    protected onInputFocusOut(event: Event): void {
        this.hideSugestions();
    }

    protected getActiveNavigationItem(): HTMLElement {
        if (this.isNavigationExist()) {
            return this.navigation[this.activeItemIndex - 1];
        }
    }

    protected getFirstProductNavigationIndex(): number {
        return this.navigation.findIndex((element: HTMLElement): boolean => {
            return element.classList.contains(`${this.jsName}__product-item--navigable`);
        });
    }

    protected getNavigation(): HTMLElement[] {
        return <HTMLElement[]> Array.from(this.getElementsByClassName(`${this.jsName}__item--navigable`))
    }

    protected updateNavigation(): void {
        if (this.isNavigationExist()) {
            this.navigation.forEach(element => {
                element.classList.remove(this.navigationActiveClass);
            });
            if (this.activeItemIndex > this.navigation.length) {
                this.activeItemIndex = 0;
                this.searchInput.focus();
                return;
            }
            if (this.activeItemIndex > 0) {
                this.navigation[this.activeItemIndex - 1].classList.add(this.navigationActiveClass);
            }
        }
    }

    protected isNavigationExist(): boolean {
        return (this.navigation && !!this.navigation.length);
    }

    protected getSearchValue(): string {
        return this.searchInput.value.trim();
    }

    protected async getSuggestions(): Promise<void> {
        const suggestQuery = this.getSearchValue();

        const urlParams = [['q', suggestQuery]];

        this.addUrlParams(urlParams);

        const response = await this.ajaxProvider.fetch(suggestQuery);

        let suggestions = JSON.parse(response).suggestion;
        this.suggestionsContainer.innerHTML = suggestions;

        this.hint = JSON.parse(response).completion;

        if (suggestions) {
            this.showSugestions();
        }

        if (this.hint) {
            this.updateHintInput();
        }

        if (this.hint == null) {
            this.setHintValue('');
        }

        this.navigation = this.getNavigation();

        this.updateNavigation();
    }

    protected addUrlParams(params: Array<Array<string>>): void {
        const baseSuggestUrl = this.getAttribute('base-suggest-url');
        let paramsString = '?';
        params.forEach( (element, index) => {
            paramsString += index == 0 ? '' : '&';
            paramsString += `${element[0]}=${element[1]}`;
        });
        this.ajaxProvider.setAttribute('url', `${baseSuggestUrl}${paramsString}`);
    }

    /**
     * Shows search suggestion(s).
     */
    showSugestions(): void {
        this.suggestionsContainer.classList.remove('is-hidden');
    }

    /**
     * Hides search suggestion(s).
     */
    hideSugestions(): void {
        this.suggestionsContainer.classList.add('is-hidden');
    }

    protected createHintInput(): void {
        this.hintInput = document.createElement('input');
        this.hintInput.classList.add(`${this.name}__hint`, 'input', 'input--expand');
        this.searchInput.parentNode.appendChild(this.hintInput);
        this.searchInput.classList.add(`${this.name}__input--transparent`);
    }

    /**
     * Sets the search suggestion(s).
     * @param value Optional data sets provided instead of the search suggestion.
     */
    updateHintInput(value?: string): void {
        let hintValue = value ? value : this.hint;
        const inputValue = this.searchInput.value;
        if (!hintValue.toLowerCase().startsWith(inputValue.toLowerCase())) {
            hintValue = '';
        }
        hintValue = hintValue.replace(hintValue.slice(0, inputValue.length), inputValue);
        this.setHintValue(hintValue);
    }

    protected setHintValue(value: string): void {
        this.hintInput.value =  value;
    }

    protected saveCurrentSearchValue(suggestQuery: string): void {
        this.currentSearchValue = suggestQuery;
    }

    /**
     * Gets a time delay for the keyup and blur events.
     */
    get debounceDelay(): number {
        return Number(this.getAttribute('debounce-delay'));
    }

    /**
     * Gets a time delay for the keydown event.
     */
    get throttleDelay(): number {
        return Number(this.getAttribute('throttle-delay'));
    }

    /**
     * Gets the number of letters which, upon entering in search field, is sufficient to send a request to the server.
     */
    get lettersTrashold(): number {
        return Number(this.getAttribute('letters-trashold'));
    }

    /**
     * Gets a querySelector of the search input field.
     */
    get searchInputSelector(): string {
        return <string> this.getAttribute('input-selector');
    }

}
