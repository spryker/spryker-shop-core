import Component from '../../../models/component';
import AjaxProvider from '../../../components/molecules/ajax-provider/ajax-provider';
import debounce from 'lodash-es/debounce';

export default class AutocompleteForm extends Component {
    ajaxProvider: AjaxProvider
    inputElement: HTMLInputElement;
    hiddenInputElement: HTMLInputElement;
    suggestionsContainer: HTMLElement;
    emptyResultsElement: HTMLElement;
    clearFieldButton: HTMLElement;

    protected readyCallback(): void {
        this.ajaxProvider = <AjaxProvider> this.querySelector(`.${this.jsName}__ajax-provider`);
        this.suggestionsContainer = <HTMLElement> this.querySelector(`.${this.jsName}__container`);
        this.inputElement = <HTMLInputElement>this.querySelector(`.${this.jsName}__input`);
        this.hiddenInputElement = <HTMLInputElement>this.querySelector(`.${this.jsName}__input-hidden`);
        this.clearFieldButton = <HTMLInputElement>this.querySelector(`.${this.jsName}__clear`);
        this.emptyResultsElement = <HTMLElement>this.querySelector(`.${this.jsName}__empty-results`);
        this.mapEvents();
    }

    protected mapEvents(): void {

        this.inputElement.addEventListener('input', debounce(() => {
            this.onInput();
        }, this.debounceDelay))

        this.inputElement.addEventListener('blur', debounce(() => {
            this.hideSugestions()
        }, this.debounceDelay))

        this.inputElement.addEventListener('focus', debounce(() => {
            if (this.inputValue.length >= this.minLetters ) {
                this.showSugestions();
            }
        }))

        this.clearFieldButton.addEventListener('click', () => {
            this.inputElement.value = ''
            this.hiddenInputElement.value = ''
            this.suggestionsContainer.innerHTML = ''
        })
    }

    protected onInput() {
        if (this.inputValue.length >= this.minLetters ) {
            this.getSuggestions();
            return false
        }
        this.hideSugestions();
    }

    protected renderEmptyResults(): void {
        this.suggestionsContainer.innerHTML = this.emptyResultsElement.innerHTML
    }

    protected showSugestions(): void {
        this.suggestionsContainer.classList.remove('is-hidden');
    }

    protected hideSugestions(): void {
        this.suggestionsContainer.classList.add('is-hidden');
    }

    protected async getSuggestions(): Promise<void> {
        this.showSugestions()

        const suggestQuery = this.inputValue;

        const urlParams = [
            [this.limitParamName,  this.limitItem],
            [this.queryParamName, this.inputValue]
        ];

        this.addUrlParams(urlParams);

        let data = await this.ajaxProvider.fetch(suggestQuery);

        this.render(data);

    }

    protected addUrlParams(params: Array<Array<string>>): void {
        const baseSuggestUrl = this.suggestionUrl;

        let paramsString = '?';
        params.forEach( (element, index) => {
            paramsString += index == 0 ? '' : '&';
            paramsString += `${element[0]}=${element[1]}`;
        });

        this.ajaxProvider.setAttribute('url', `${this.suggestionUrl}${paramsString}`);
    }

    protected render(data): void {
        let dataList = this.objectKey ? data[this.objectKey] : data;

        if (!dataList || Object.keys(dataList).length === 0) {
            this.renderEmptyResults();
            return;
        }

        let renderResults = '';
        dataList.forEach((item) => {
            const itemRender = this.renderTemplate.replace(/\${([^}]*)}/g, (r, k) => item[k]);
            renderResults += `<li data-value="${item[this.valueKey]}" class="autocomplete-form__item">${itemRender}</li>`;
        })

        this.suggestionsContainer.innerHTML = `<ul class="list">${renderResults}</ul>`;
        this.onRenderResultsClick()
    }

    protected onRenderResultsClick(): void {
        const dropdownElements = this.suggestionsContainer.querySelectorAll('.autocomplete-form__item');

        for (let i = 0; i < dropdownElements.length; ++i) {
            let element = dropdownElements[i];
            element.addEventListener('click', (evt: Event) => {
                const dataValue = evt.srcElement.getAttribute('data-value');

                this.hiddenInputElement.value = dataValue;
                this.inputElement.value = evt.srcElement.textContent;
            })
        }
    }

    protected get minLetters(): number {
        return Number(this.getAttribute('min-letters'))
    }

    protected get suggestionUrl() {
        return this.getAttribute('suggestion-url')
    }

    protected get limitItem() {
        return this.getAttribute('limit-items')
    }

    protected get limitParamName() {
        return this.getAttribute('limit-param-name')
    }

    protected get renderTemplate() {
        return this.getAttribute('render-template')
    }

    protected get valueKey() {
        return this.getAttribute('value-key')
    }

    protected get objectKey() {
        return this.getAttribute('object-key')
    }

    protected get queryParamName() {
        return this.getAttribute('query-param-name')
    }

    protected get inputValue(): string {
        return this.inputElement.value.trim()
    }

    get debounceDelay(): number {
        return Number(this.getAttribute('debounce-delay'));
    }

}
