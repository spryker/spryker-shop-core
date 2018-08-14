import Component from '../../../models/component';
import AjaxProvider from '../../../components/molecules/ajax-provider/ajax-provider';
import debounce from 'lodash-es/debounce';

export default class AutocompleteForm extends Component {
    ajaxProvider: AjaxProvider
    inputElement: HTMLInputElement;
    hiddenInputElement: HTMLInputElement;
    suggestionsContainer: HTMLElement;
    target: any;

    protected readyCallback(): void {
        this.ajaxProvider = <AjaxProvider> this.querySelector(`.${this.jsName}__provider`);
        this.suggestionsContainer = <HTMLElement> this.querySelector(`.${this.jsName}__container`);
        this.inputElement = <HTMLInputElement>this.querySelector(`.${this.jsName}__input`);
        this.hiddenInputElement = <HTMLInputElement>this.querySelector(`.${this.jsName}__input-hidden`);
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.inputElement.addEventListener('input', debounce(() => this.onInput(), this.debounceDelay));
        this.inputElement.addEventListener('blur', debounce(() => this.hideSugestions(), this.debounceDelay));
        this.inputElement.addEventListener('focus',  () => this.onFocus());
    }

    protected onFocus() {
        if (this.inputValue.length >= this.minLetters ) {
            this.showSugestions();
        }
    }

    protected onInput() {
        if (this.inputValue.length >= this.minLetters ) {
            this.getSuggestions();
            return false
        }
        this.hideSugestions();
    }

    protected showSugestions(): void {
        this.suggestionsContainer.classList.remove('is-hidden');
        this.onClick();
    }

    protected hideSugestions(): void {
        this.suggestionsContainer.classList.add('is-hidden');
    }

    protected async getSuggestions(): Promise<void> {
        this.showSugestions()
        let params = {};
        params[this.queryParamName] = this.inputValue;

        await this.ajaxProvider.fetch(params);
    }


    protected onClick(): void {
        const self = this;
        const items = Array.from(this.suggestionsContainer.querySelectorAll(`.${this.jsName}__item`));

        items.forEach((item) => item.addEventListener('click', (e: Event) => self.handlerClick(e)));
    }

    protected handlerClick(e): void {
        const dataValue = e.target.getAttribute(this.valueDataAttribute);
        this.hiddenInputElement.value = dataValue;
        this.inputElement.value = e.srcElement.textContent;
    }

    protected get minLetters(): number {
        return Number(this.getAttribute('min-letters'))
    }

    protected get inputValue(): string {
        return this.inputElement.value.trim()
    }

    protected get queryParamName(): string {
        return this.getAttribute('query-param-name')
    }

    protected get valueDataAttribute(): string {
        return this.getAttribute('value-data-attribute')
    }

    get debounceDelay(): number {
        return Number(this.getAttribute('debounce-delay'));
    }

}
