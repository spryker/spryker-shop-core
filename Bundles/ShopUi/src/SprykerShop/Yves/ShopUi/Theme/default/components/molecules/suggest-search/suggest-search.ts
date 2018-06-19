import Component from '../../../models/component';
import AjaxProvider from '../../../components/molecules/ajax-provider/ajax-provider';
import { throttle, debounce } from 'throttle-debounce';

interface keyCodesMap {
    [keyCode: number]: string;
}

export default class SuggestSearch extends Component {
    readonly lettersTrashold: number
    readonly delay: number
    readonly keyboardCodes: keyCodesMap
    searchInput: HTMLInputElement
    hintInput: HTMLInputElement
    suggestionsContainer: HTMLElement
    ajaxProvider: AjaxProvider
    currentSearchValue: string
    searchInputSelector: string
    hint: string
    navigation: HTMLElement[]
    activeItemIndex: number


    constructor() {
        super();

        this.lettersTrashold = 2;
        this.delay = 500;
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

    readyCallback(): void {
        this.ajaxProvider = <AjaxProvider> this.querySelector(`.${this.componentSelector}__provider`);
        this.searchInputSelector = <string> this.ajaxProvider.getAttribute('inputSelector');
        this.suggestionsContainer = <HTMLElement> this.querySelector(`.${this.componentSelector}__container`);
        this.searchInput = <HTMLInputElement> document.querySelector(this.searchInputSelector);
        this.createHintInput();
        this.mapEvents();
    }

    mapEvents(): void {
        this.searchInput.addEventListener('keyup', debounce(this.delay, (event: Event) => this.onInputKeyUp(event)));
        this.searchInput.addEventListener('keydown', throttle(this.delay, (event: Event) => this.onInputKeyDown(<KeyboardEvent> event)));
        this.searchInput.addEventListener('blur', (event: Event) => this.onInputFocusOut(event));
        this.searchInput.addEventListener('focus', (event: Event) => this.onInputFocusIn(event));
        this.searchInput.addEventListener('click', (event: Event) => this.onInputClick(event));
    }

    onInputKeyUp(event: Event): void {
        const suggestQuery = this.getSearchValue();
        if (suggestQuery != this.currentSearchValue) {
            this.getSuggestions(<HTMLInputElement> event.target);
        }

    }

    onInputKeyDown(event: KeyboardEvent): void {
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

    onInputClick(event: KeyboardEvent): void {
        this.activeItemIndex = 0;
        this.updateNavigation();
    }

    onTab(event: KeyboardEvent): boolean {
        this.searchInput.value = this.hint;
        event.preventDefault();
        return false;
    }

    onArrowUp(event: KeyboardEvent) {
        this.activeItemIndex--;
        this.updateNavigation();
    }

    onArrowDown(event: KeyboardEvent) {
        this.activeItemIndex++;
        this.updateNavigation();
    }

    onArrowLeft(event: KeyboardEvent) {
        this.activeItemIndex = 1;
        this.updateNavigation();
    }

    onArrowRight(event: KeyboardEvent) {
        this.activeItemIndex = 1;
        this.updateNavigation();
    }

    onEnter(event: KeyboardEvent) {
        this.getActiveNavigationItem().click();
        event.preventDefault();
    }

    getActiveNavigationItem() {
        return this.navigation[this.activeItemIndex - 1];
    }

    onInputFocusIn(event: Event): void {
        this.activeItemIndex = 0;
    }

    onInputFocusOut(event: Event): void {
        this.hideSugestions();
    }

    updateNavigation() {
        if (this.navigation && this.navigation.length) {
            this.navigation.forEach(element => {
                element.classList.remove('is-active');
            });
            if (this.activeItemIndex > 0) {
                this.navigation[this.activeItemIndex - 1].classList.add('is-active');
            }
        }
    }

    getSearchValue(): string {
        return this.searchInput.value.trim();
    }

    async getSuggestions(target: HTMLInputElement): Promise<void> {
        const suggestQuery = this.getSearchValue();
        this.currentSearchValue = suggestQuery;

        if (suggestQuery.length >= this.lettersTrashold) {
            const urlParams = [['q', suggestQuery]];

            this.addUrlParams(urlParams);

            const response = await this.ajaxProvider.fetch(suggestQuery);

            this.suggestionsContainer.innerHTML = JSON.parse(response).suggestion;
            this.hint = JSON.parse(response).completion;

            if (this.hint && this.hint.length > this.lettersTrashold) {
                this.showSugestions();
                this.updateHintInput();
            }

            if (this.hint == null) {
                this.updateHintInput('');
            }

            this.navigation = <HTMLElement[]> Array.from(this.getElementsByClassName('js-suggestions-navigable'));

            this.updateNavigation();

            return;
        }

        this.hideSugestions();
        this.updateHintInput('');
    }

    addUrlParams(params: Array<Array<string>>): void {
        const baseSuggestUrl = this.ajaxProvider.getAttribute('baseSuggestUrl');
        let paramsString = '?';
        params.forEach( (element, index) => {
            paramsString += index == 0 ? '' : '&';
            paramsString += `${element[0]}=${element[1]}`;
        });
        this.ajaxProvider.setAttribute('url', `${baseSuggestUrl}${paramsString}`);
    }

    showSugestions(): void {
        this.suggestionsContainer.classList.remove('is-hidden');
    }

    hideSugestions(): void {
        this.suggestionsContainer.classList.add('is-hidden');
    }

    createHintInput(): void {
        this.hintInput = document.createElement('input');
        this.hintInput.classList.add(`${this.componentName}__hint`, 'input', 'input--expand');
        this.searchInput.parentNode.appendChild(this.hintInput);
        this.searchInput.classList.add(`${this.componentName}__input--transparent`);
    }

    updateHintInput(value?): void {
        const hintValue = value ? value : this.hint;
        this.setHintValue(hintValue);
    }

    setHintValue(value: string): void {
        this.hintInput.value =  value;
    }
}
