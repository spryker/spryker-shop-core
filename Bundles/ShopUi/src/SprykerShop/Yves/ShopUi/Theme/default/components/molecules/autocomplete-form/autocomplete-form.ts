import Component from '../../../models/component';
import AjaxProvider from '../../../components/molecules/ajax-provider/ajax-provider';
import debounce from 'lodash-es/debounce';

export default class AutocompleteForm extends Component {
    ajaxProvider: AjaxProvider
    inputElement: HTMLInputElement;
    hiddenInputElement: HTMLInputElement;
    suggestionsContainer: HTMLElement;

    protected readyCallback(): void {
        this.ajaxProvider = <AjaxProvider> this.querySelector(`.${this.jsName}__provider`);
        this.suggestionsContainer = <HTMLElement> this.querySelector(`.${this.jsName}__container`);
        this.inputElement = <HTMLInputElement>this.querySelector(`.${this.jsName}__input`);
        this.hiddenInputElement = <HTMLInputElement>this.querySelector(`.${this.jsName}__input-hidden`);
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.inputElement.addEventListener('input', debounce(() => this.onInput(), this.debounceDelay));
        this.inputElement.addEventListener('blur', debounce(() => this.onHideSugestions(), this.debounceDelay));
        this.inputElement.addEventListener('focus',  () => this.onFocus());
    }

    protected onFocus(): void {
        if (this.inputValue.length >= this.minLetters) {
            this.onShowSugestions();
            return;
        }
    }

    protected onInput(): void {
        if (this.inputValue.length >= this.minLetters ) {
            this.loadSuggestions();
            return;
        }
        this.onHideSugestions();
    }

    protected onShowSugestions(): void {
        this.suggestionsContainer.classList.remove('is-hidden');
        this.mapItemEvents();
    }

    protected onHideSugestions(): void {
        this.suggestionsContainer.classList.add('is-hidden');
    }

    async loadSuggestions(): Promise<void> {
        this.onShowSugestions();
        let params = {};
        params[this.queryParamName] = this.inputValue;
        await this.ajaxProvider.fetch(params);
    }

    protected mapItemEvents(): void {
        const self = this;
        const items = Array.from(this.suggestionsContainer.querySelectorAll(this.itemSelector));
        items.forEach((item: HTMLElement) => item.addEventListener('click', (e: Event) => self.onItemClick(e)));
    }

    protected onItemClick(e: Event): void {
        const dataTarget = <HTMLElement>e.target;
        const textTarget = <HTMLElement>e.srcElement;
        const data = dataTarget.getAttribute(this.valueDataAttribute);
        const text = textTarget.textContent;

        this.setInputs(data, text);
    }

    setInputs(data: string, text: string): void {
        this.inputElement.value = text;
        this.hiddenInputElement.value = data;
    }

    protected get minLetters(): number {
        return Number(this.getAttribute('min-letters'));
    }

    protected get inputValue(): string {
        return this.inputElement.value.trim();
    }

    protected get queryParamName(): string {
        return this.getAttribute('query-param-name');
    }

    protected get valueDataAttribute(): string {
        return this.getAttribute('value-data-attribute');
    }

    protected get itemSelector(): string {
        return this.getAttribute('item-selector');
    }

    get debounceDelay(): number {
        return Number(this.getAttribute('debounce-delay'));
    }

}
