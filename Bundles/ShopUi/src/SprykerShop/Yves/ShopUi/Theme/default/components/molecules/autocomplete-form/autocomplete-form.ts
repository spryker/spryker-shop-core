import Component from '../../../models/component';
import AjaxProvider from '../../../components/molecules/ajax-provider/ajax-provider';
import debounce from 'lodash-es/debounce';

export default class AutocompleteForm extends Component {
    ajaxProvider: AjaxProvider
    inputElement: HTMLInputElement;
    hiddenInputElement: HTMLInputElement;
    suggestionsContainer: HTMLElement;
    ajaxUrl: string;
    target: any;

    protected readyCallback(): void {
        this.ajaxProvider = <AjaxProvider> this.querySelector(`.${this.jsName}__provider`);
        this.suggestionsContainer = <HTMLElement> this.querySelector(`.${this.jsName}__container`);
        this.inputElement = <HTMLInputElement>this.querySelector(`.${this.jsName}__input`);
        this.hiddenInputElement = <HTMLInputElement>this.querySelector(`.${this.jsName}__input-hidden`);
        this.ajaxUrl = this.ajaxProvider.getAttribute('url');
        this.addEventListener('click', this.selectElement);
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
    }

    protected hideSugestions(): void {
        this.suggestionsContainer.classList.add('is-hidden');
    }

    protected async getSuggestions(): Promise<void> {
        this.showSugestions()

        await this.ajaxProvider.fetch({
            'q': this.inputValue
        });
    }

    selectElement(e): void {
        this.target = e.target;
        while (this.target != this) {
            if (this.target.hasAttribute("data-value")) {
                this.inputElement.value = this.target.textContent;
                this.hiddenInputElement.value = this.target.getAttribute("data-value");
            }
            this.target = this.target.parentNode;
        }
    }

    protected get minLetters(): number {
        return Number(this.getAttribute('min-letters'))
    }

    protected get inputValue(): string {
        return this.inputElement.value.trim()
    }

    get debounceDelay(): number {
        return Number(this.getAttribute('debounce-delay'));
    }

}
