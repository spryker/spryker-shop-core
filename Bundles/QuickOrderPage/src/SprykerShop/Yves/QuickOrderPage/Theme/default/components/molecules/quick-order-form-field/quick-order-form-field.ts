import Component from 'ShopUi/models/component';
import AutocompleteForm from 'ShopUi/components/molecules/autocomplete-form/autocomplete-form';

export default class QuickOrderFormField extends Component {
    autocompleteForm: AutocompleteForm;
    selectedItem: HTMLElement;
    addIdEvent: CustomEvent;
    autocompleteFormInput: HTMLFormElement;

    protected readyCallback(): void {
        this.autocompleteForm = <AutocompleteForm>this.querySelector('autocomplete-form');
        this.autocompleteFormInput = <HTMLFormElement>this.autocompleteForm.querySelector(`.${this.autocompleteForm.jsName}__input`);

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.createCustomEvents();
        this.addEventListener('click', (event: Event) => this.componentClickHandler(event));

        this.autocompleteFormInput.addEventListener('input', () => {
            this.autocompleteFormInputChangeHandler();
        });
    }

    private createCustomEvents(): void {
        this.addIdEvent = <CustomEvent>new CustomEvent("addId", {
            detail: {
                username: "addId"
            }
        });
    }

    private componentClickHandler(event: Event): void {
        this.selectedItem = <HTMLElement>event.target;

        if (this.selectedItem.matches(this.dropDownItemSelector)) {
            event.stopPropagation();

            this.changeDataId(this.selectedId);
        }
    }

    private autocompleteFormInputChangeHandler(): void {
        if(this.autocompleteFormInput.value.length <= this.autocompleteForm.minLetters) {
            this.changeDataId('');
        }
    }

    private changeDataId(id: string): void {
        this.autocompleteForm.hiddenInputElement.setAttribute('data-id', id);
        this.autocompleteForm.hiddenInputElement.dispatchEvent(this.addIdEvent);
    }

    get dropDownItemSelector(): string {
        return this.autocompleteForm.getAttribute('item-selector');
    }

    get selectedId(): string {
        return this.selectedItem.getAttribute('data-id-product');
    }

    get productId(): string {
        return this.autocompleteForm.hiddenInputElement.dataset.id;
    }
}
