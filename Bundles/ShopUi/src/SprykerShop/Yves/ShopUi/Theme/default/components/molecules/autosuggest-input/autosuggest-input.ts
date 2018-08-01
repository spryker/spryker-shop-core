import Component from '../../../models/component';
import AjaxFetcher from './ajax-fetcher';

export default class AutosuggestInput extends Component {
    protected ajaxFetcher: AjaxFetcher;
    protected inputElement: HTMLInputElement;
    protected hiddenInputElement: HTMLInputElement;
    protected dropdownElement: HTMLElement;
    protected emptyResultsElement: HTMLElement;
    protected clearFieldButton: HTMLElement;

    constructor() {
        super();

        this.ajaxFetcher = new AjaxFetcher();
        this.inputElement = <HTMLInputElement>this.querySelector('input[name=query]');
        this.hiddenInputElement = <HTMLInputElement>this.querySelector(`input[name="${this.selectedValueKey}"]`);
        this.dropdownElement = <HTMLElement>this.querySelector('.dropdown');
        this.emptyResultsElement = <HTMLElement>this.querySelector('.empty-results');
        this.clearFieldButton = <HTMLElement>this.querySelector('.clear-field');
    }

    protected readyCallback(): void {
        this.mapEvents()
    }

    protected hideDropdown(): void {
        this.dropdownElement.className = 'dropdown hidden'
    }

    protected mapEvents(): void {
        this.inputElement.addEventListener('focus', () => {
            this.dropdownElement.className = 'dropdown'
        })

        this.inputElement.addEventListener('input', debounce(() => {
            this.onInput()
        }, 200))

        this.clearFieldButton.addEventListener('click', () => {
            this.inputElement.value = ''
            this.hiddenInputElement.value = ''
            this.dropdownElement.innerHTML = ''
        })
    }

    protected onInput(): void {
        if (this.inputValue.length < this.minLetters) {
            return;
        }

        this.fetchSuggestions()
    }

    protected fetchSuggestions(): void {
        this.ajaxFetcher
            .fetch(this.buildUrl())
            .then(data => {
                this.render(data)
            })
            .catch(error => {
                console.log(error)
            })
    }

    protected buildUrl(): string {
        return `${this.suggestionUrl}?${this.limitParamName}=${this.limit}&${this.queryParamName}=${this.inputValue}`
    }

    protected render(data: object): void {
        let dataList = this.objectKey ? data[this.objectKey] : data;

        if (!dataList || Object.keys(dataList).length === 0) {
            this.renderEmptyResults();
            return;
        }

        let renderResults = '';
        dataList.forEach((item) => {
            const itemRender = this.renderTemplate.replace(/\${([^}]*)}/g, (r, k) => item[k]);
            renderResults += `<li data-value="${item[this.valueKey]}" class="autocomplete-item">${itemRender}</li>`;
        })

        this.dropdownElement.innerHTML = `<ul>${renderResults}</ul>`;
        this.onRenderResultsClick()
    }

    protected onRenderResultsClick(): void {
        const dropdownElements = this.dropdownElement.querySelectorAll('.autocomplete-item');

        for (let i = 0; i < dropdownElements.length; ++i) {
            let element = dropdownElements[i];
            element.addEventListener('click', (evt: Event) => {
                const dataValue = evt.srcElement.getAttribute('data-value');

                this.hiddenInputElement.value = dataValue;
                this.inputElement.value = evt.srcElement.textContent;
                this.hideDropdown()
            })
        }
    }

    protected renderEmptyResults(): void {
        this.dropdownElement.innerHTML = this.emptyResultsElement.innerHTML
    }

    protected get minLetters(): number {
        return Number(this.getAttribute('minLetters'))
    }

    protected get suggestionUrl() {
        return this.getAttribute('suggestionUrl')
    }

    protected get limit() {
        return this.getAttribute('limit')
    }

    protected get limitParamName() {
        return this.getAttribute('limitParamName')
    }

    protected get queryParamName() {
        return this.getAttribute('queryParamName')
    }

    protected get renderTemplate() {
        return this.getAttribute('renderTemplate')
    }

    protected get valueKey() {
        return this.getAttribute('valueKey')
    }

    protected get objectKey() {
        return this.getAttribute('objectKey')
    }
    protected get selectedValueKey() {
        return this.getAttribute('selectedValueKey')
    }

    protected get inputValue(): string {
        return this.inputElement.value
    }
}

function debounce<F extends (...params: any[]) => void>(fn: F, delay: number) {
    let timeoutID: number = null;
    return function (this: any, ...args: any[]) {
        clearTimeout(timeoutID);
        timeoutID = window.setTimeout(() => fn.apply(this, args), delay);
    } as F;
}
