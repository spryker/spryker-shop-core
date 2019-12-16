/* tslint:disable: max-file-line-count */
import Component from '../../../models/component';
import AjaxProvider from '../../../components/molecules/ajax-provider/ajax-provider';
import debounce from 'lodash-es/debounce';
import throttle from 'lodash-es/throttle';

export default class SuggestSearch extends Component {
    /**
     * The search input element.
     */
    searchInput: HTMLInputElement;

    /**
     * The hint input element.
     */
    hintInput: HTMLInputElement;

    /**
     * The container of the suggestions items.
     */
    suggestionsContainer: HTMLElement;

    /**
     * Performs the Ajax operations.
     */
    ajaxProvider: AjaxProvider;

    /**
     * The current value of the search input element.
     */
    currentSearchValue: string;

    /**
     * A content of the hint input element.
     */
    hint: string;

    /**
     * The list of the search suggestions.
     */
    navigation: HTMLElement[];

    /**
     * The index of the active suggestion item.
     */
    activeItemIndex: number = 0;

    /**
     * The class name of the active suggestion item.
     */
    navigationActiveClass: string;

    protected readyCallback(): void {
        this.ajaxProvider = <AjaxProvider> this.getElementsByClassName(`${this.jsName}__ajax-provider`)[0];
        this.suggestionsContainer = <HTMLElement> this.getElementsByClassName(`${this.jsName}__container`)[0];
        /* tslint:disable: deprecation */
        this.searchInput = <HTMLInputElement> (this.searchInputClassName ?
            document.getElementsByClassName(this.searchInputClassName)[0] :
            document.querySelector(this.searchInputSelector));
        /* tslint:enable: deprecation */
        this.navigationActiveClass = `${this.name}__item--active`;
        this.createHintInput();
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.searchInput.addEventListener('keyup', debounce(() => this.onInputKeyUp(), this.debounceDelay));
        this.searchInput.addEventListener('keydown', throttle((event: Event) => {
            this.onInputKeyDown(<KeyboardEvent> event);
        }, this.throttleDelay));
        this.searchInput.addEventListener('blur', debounce(() => this.onInputFocusOut(), this.debounceDelay));
        this.searchInput.addEventListener('focus', () => this.onInputFocusIn());
        this.searchInput.addEventListener('click', () => this.onInputClick());
    }

    protected async onInputKeyUp(): Promise<void> {
        const suggestQuery = this.getSearchValue();

        if (suggestQuery !== this.currentSearchValue && suggestQuery.length >= this.lettersTrashold) {
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
        switch (event.key) {
            case 'Enter': this.onEnter(event); break;
            case 'Tab': this.onTab(event); break;
            case 'ArrowUp': this.onArrowUp(); break;
            case 'ArrowDown': this.onArrowDown(); break;
            case 'ArrowLeft': this.onArrowLeft(); break;
            case 'ArrowRight': this.onArrowRight(); break;
            case 'Backspace': this.onBackspace(); break;
        }
    }

    protected onInputClick(): void {
        this.activeItemIndex = 0;
        if (this.isNavigationExist()) {
            this.updateNavigation();
            this.showSugestions();
        }
    }

    protected onTab(event: KeyboardEvent): boolean {
        if (this.hint) {
            this.searchInput.value = this.hint;
        }
        event.preventDefault();

        return false;
    }

    protected onArrowUp(): void {
        this.activeItemIndex = this.activeItemIndex > 0 ? this.activeItemIndex - 1 : 0;
        this.updateNavigation();
    }

    protected onArrowDown(): void {
        if (this.navigation) {
            this.activeItemIndex = this.activeItemIndex < this.navigation.length ? this.activeItemIndex + 1 : 0;
        }
        this.updateNavigation();
    }

    protected onArrowLeft(): void {
        this.activeItemIndex = 1;
        this.updateNavigation();
    }

    protected onArrowRight(): void {
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

    protected onBackspace(): void {
        if (!!this.searchInput.value) {
            this.setHintValue('');
        }
    }

    protected onInputFocusIn(): void {
        this.activeItemIndex = 0;
    }

    protected onInputFocusOut(): void {
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
        return <HTMLElement[]> Array.from(this.getElementsByClassName(`${this.jsName}__item--navigable`));
    }

    protected updateNavigation(): void {
        if (this.isNavigationExist()) {
            this.navigation.forEach(element => element.classList.remove(this.navigationActiveClass));
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

        this.ajaxProvider.queryParams.set('q', suggestQuery);

        const response = await this.ajaxProvider.fetch(suggestQuery);
        const suggestions = JSON.parse(response).suggestion;

        this.suggestionsContainer.innerHTML = suggestions;
        this.hint = JSON.parse(response).completion;
        if (suggestions) {
            this.showSugestions();
        }
        if (this.hint) {
            this.updateHintInput();
        }
        if (this.hint == undefined) {
            this.setHintValue('');
        }
        this.navigation = this.getNavigation();
        this.updateNavigation();
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
        this.hintInput.classList.add(`${this.name}__hint`);
        this.searchInput.parentNode.appendChild(this.hintInput).setAttribute('readonly', 'readonly');
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
     *
     * @deprecated Use searchInputClassName() instead.
     */
    get searchInputSelector(): string {
        return <string> this.getAttribute('input-selector');
    }
    protected get searchInputClassName(): string {
        return this.getAttribute('input-class-name');
    }
}
