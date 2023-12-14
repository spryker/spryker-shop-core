import Component from 'ShopUi/models/component';

export default class SeparateReturnsByMerchant extends Component {
    protected checkboxes: HTMLInputElement[];
    protected merchantReferenceName: string;
    protected checkedItems: HTMLInputElement[];
    protected checkboxComponentClassname: string;
    protected checkboxDisabledComponentClassname: string;

    protected readyCallback() {}

    protected init() {
        this.checkedItems = [];
        this.merchantReferenceName = this.merchantReference;
        this.checkboxes = <HTMLInputElement[]>Array.from(document.getElementsByClassName(this.checkboxClassname));
        this.checkboxComponentClassname = this.checkboxComponentClass;
        this.checkboxDisabledComponentClassname = this.checkboxDisabledComponentClass;

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.checkboxToggleAction();
    }

    protected checkboxToggleAction(): void {
        this.checkboxes.map((checkbox) => {
            checkbox.addEventListener('change', (event) => {
                const target = <HTMLInputElement>event.target;
                target.checked ? this.onAddCheckedItem(target) : this.onRemoveCheckedItems();
            });
        });
    }

    protected onAddCheckedItem(item: HTMLInputElement): void {
        this.checkedItems.push(item);
        this.disableItem(item);
    }

    protected onRemoveCheckedItems(): void {
        this.checkedItems = this.checkedItems.filter((item) => {
            return item.checked;
        });

        if (this.checkedItems.length) {
            return;
        }

        this.enableAllItems();
    }

    protected disableItem(target: HTMLInputElement): void {
        const currentMerchantReference = target.getAttribute(this.merchantReference);

        const checkboxesToDisable = this.checkboxes.filter((checkbox) => {
            return checkbox.getAttribute(this.merchantReference) !== currentMerchantReference;
        });

        checkboxesToDisable.map((checkbox) => {
            checkbox.disabled = true;
            checkbox
                .closest(`.${this.checkboxComponentClassname}`)
                .classList.add(`${this.checkboxDisabledComponentClassname}`);
        });
    }

    protected enableAllItems(): void {
        this.checkboxes.map((checkbox) => {
            if (!checkbox.hasAttribute(this.isReturnable)) {
                return;
            }

            checkbox.disabled = false;
            checkbox
                .closest(`.${this.checkboxComponentClassname}`)
                .classList.remove(`${this.checkboxDisabledComponentClassname}`);
        });
    }

    protected get merchantReference(): string {
        return this.getAttribute('merchant-reference-attribute-name');
    }

    protected get checkboxClassname(): string {
        return this.getAttribute('checkbox-classname');
    }

    protected get checkboxComponentClass(): string {
        return this.getAttribute('checkbox-component-classname');
    }

    protected get checkboxDisabledComponentClass(): string {
        return this.getAttribute('checkbox-component-disabled-classname');
    }

    protected get isReturnable(): string {
        return this.getAttribute('is-returnable-attribute-name');
    }
}
