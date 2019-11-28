/* tslint:disable */

/*
 * @tag example This code provides example of using the Product Measurement Unit.
 */

import Component from 'ShopUi/models/component';

interface UnitTranslationsJSONData {
    GRAM: string;
    ITEM: string;
    KILO: string;
}

interface BaseUnit {
    code: string;
    default_precision: number;
    id_product_measurement_unit: number;
    name: string;
}

interface SalesUnit {
    conversion: number;
    fk_product: number;
    fk_product_measurement_base_unit?: number;
    fk_product_measurement_unit?: number;
    id_product_measurement_sales_unit: number;
    is_default: boolean;
    is_displayed: boolean;
    precision: number;
    product_measurement_base_unit?: number;
    product_measurement_unit: BaseUnit;
    store_relation?: number;
    value?: string;
}

interface ProductQuantityStorage {
    id_product: number;
    quantity_interval?: number;
    quantity_max?: number;
    quantity_min?: number;
}

interface MeasurementJSONData {
    baseUnit: BaseUnit;
    salesUnits: SalesUnit[];
    productQuantityStorage: ProductQuantityStorage;
}

export default class MeasurementQuantitySelector extends Component {
    /**
     * The input element of the sales quantity value.
     */
    protected qtyInSalesUnitInput: HTMLInputElement;
    /**
     * The input element of the base quantity value.
     */
    protected qtyInBaseUnitInput: HTMLInputElement;
    /**
     * The input element of the measurement unit.
     */
    protected measurementUnitInput: HTMLSelectElement;
    /**
     * The "Add to cart" button.
     */
    protected addToCartButton: HTMLButtonElement;
    /**
     * The quantity step container.
     */
    protected quantityBetweenUnits: HTMLElement;
    /**
     * The minimum quantity container.
     */
    protected minimumQuantity: HTMLElement;
    /**
     * The maximum quantity container.
     */
    protected maximumQuantity: HTMLElement;
    /**
     * The measurement unit correct choice container.
     */
    protected measurementUnitChoice: HTMLElement;
    /**
     * The base unit object.
     */
    protected baseUnit: BaseUnit;
    /**
     * The sales units object.
     */
    protected salesUnits: SalesUnit[];
    /**
     * The current sales unit object.
     */
    protected currentSalesUnit: SalesUnit;
    /**
     * The product quantity storage object.
     */
    protected productQuantityStorage: ProductQuantityStorage;
    /**
     * The current value.
     */
    protected currentValue: number;
    /**
     * The translations object.
     */
    protected translations: UnitTranslationsJSONData;
    /**
     * The number of decimals characters after dot for rounded numbers.
     */
    protected readonly decimals: number = 4;
    /**
     * The number coefficient for math actions with numbers.
     */
     protected readonly factor: number = 10;
    /**
     * The number coefficients for exponentiation actions of a factor number.
     */
    protected readonly degree: number[] = [2, 3];

    protected readyCallback(event?: Event): void {}

    protected init(): void {
        this.qtyInSalesUnitInput = <HTMLInputElement>
            this.getElementsByClassName(`${this.jsName}__sales-unit-quantity`)[0];
        this.qtyInBaseUnitInput = <HTMLInputElement>
            this.getElementsByClassName(`${this.jsName}__base-unit-quantity`)[0];
        this.measurementUnitInput = <HTMLSelectElement>
            this.getElementsByClassName(`${this.jsName}__select-measurement-unit`)[0];
        this.addToCartButton = <HTMLButtonElement>
            this.getElementsByClassName(`${this.jsName}__add-to-cart-button`)[0];

        this.quantityBetweenUnits = <HTMLElement>
            this.getElementsByClassName(`${this.jsName}__quantity-between-units`)[0];
        this.minimumQuantity = <HTMLElement>
            this.getElementsByClassName(`${this.jsName}__minimum-quantity`)[0];
        this.maximumQuantity = <HTMLElement>
            this.getElementsByClassName(`${this.jsName}__maximum-quantity`)[0];
        this.measurementUnitChoice = <HTMLElement>
            this.getElementsByClassName(`${this.jsName}__measurement-unit-choice`)[0];

        this.initJson();
        this.initTranslations();
        this.initCurrentSalesUnit();
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.qtyInSalesUnitInput.addEventListener('change', () => this.qtyInputChange());
        this.measurementUnitInput.addEventListener('change', (event: Event) =>
            this.measurementUnitInputChange(event));
    }

    protected initJson(): void {
        const measurementUnitData = <MeasurementJSONData>JSON.parse(this.measurementJSONString);

        this.baseUnit = measurementUnitData.baseUnit;
        this.salesUnits = measurementUnitData.salesUnits;
        this.productQuantityStorage = measurementUnitData.productQuantityStorage;
    }

    protected initTranslations(): void {
        this.translations = <UnitTranslationsJSONData>JSON.parse(
            this.getElementsByClassName(`${this.jsName}__measurement-unit-translation`)[0].innerHTML
        );
    }

    protected initCurrentSalesUnit(): void {
        this.salesUnits.forEach((salesUnit: SalesUnit) => {
            if (salesUnit.is_default) {
                this.currentSalesUnit = salesUnit;
            }
        });
    }

    protected qtyInputChange(qtyInSalesUnits?: number): void {
        if (typeof qtyInSalesUnits === 'undefined') {
            qtyInSalesUnits = +this.qtyInSalesUnitInput.value;
        }
        let error = false;
        const qtyInBaseUnits = this.multiply(qtyInSalesUnits, +this.currentSalesUnit.conversion);
        if ((qtyInBaseUnits - this.getMinQuantity()) % this.getQuantityInterval() !== 0) {
            error = true;
            this.hideNotifications();
            this.quantityBetweenUnits.classList.remove('is-hidden');
        } else if (qtyInBaseUnits < this.getMinQuantity()) {
            error = true;
            this.hideNotifications();
            this.minimumQuantity.classList.remove('is-hidden');
        } else if (this.getMaxQuantity() > 0 && qtyInBaseUnits > this.getMaxQuantity()) {
            error = true;
            this.hideNotifications();
            this.maximumQuantity.classList.remove('is-hidden');
        }

        if (error) {
            this.addToCartButton.setAttribute('disabled', 'disabled');
            this.askCustomerForCorrectInput(qtyInSalesUnits);

            return;
        }

        this.qtyInBaseUnitInput.value = qtyInBaseUnits.toString();
        this.addToCartButton.removeAttribute('disabled');
        this.hideNotifications();
    }

    protected hideNotifications(): void {
        this.measurementUnitChoice.classList.add('is-hidden');
        this.quantityBetweenUnits.classList.add('is-hidden');
        this.minimumQuantity.classList.add('is-hidden');
        this.maximumQuantity.classList.add('is-hidden');
    }

    protected askCustomerForCorrectInput(qtyInSalesUnits: number): void {
        const choicesList = this.measurementUnitChoice.getElementsByClassName('list')[0];
        const currentChoice = this.measurementUnitChoice.getElementsByClassName(
            `${this.jsName}__current-choice`
        )[0];
        const minChoice = this.getMinChoice(qtyInSalesUnits);
        const maxChoice = this.getMaxChoice(qtyInSalesUnits, minChoice);
        choicesList.innerHTML = '';
        currentChoice.innerHTML = '';
        currentChoice.textContent = `${this.round(qtyInSalesUnits, this.decimals)} ${this.getUnitName(
            this.currentSalesUnit.product_measurement_unit.code
        )}`;

        const choiceElements = [];
        choiceElements.push(this.createChoiceElement(minChoice));
        if (maxChoice !== minChoice) {
            choiceElements.push(this.createChoiceElement(maxChoice));
        }

        choiceElements.forEach(element => (element !== null) ? choicesList.appendChild(element) : undefined);

        this.measurementUnitChoice.classList.remove('is-hidden');
    }

    private createChoiceElement(qtyInBaseUnits: number): HTMLSpanElement {
        if (qtyInBaseUnits > 0) {
            const choiceElem = document.createElement('span');
            const qtyInSalesUnits = qtyInBaseUnits / this.currentSalesUnit.conversion;
            const measurementSalesUnitName = this.getUnitName(this.currentSalesUnit.product_measurement_unit.code);
            const measurementBaseUnitName = this.getUnitName(this.baseUnit.code);

            choiceElem.classList.add('link');
            choiceElem.setAttribute('data-base-unit-qty', qtyInBaseUnits.toString());
            choiceElem.setAttribute('data-sales-unit-qty', qtyInSalesUnits.toString());
            choiceElem.textContent = `(${this.round(qtyInSalesUnits, this.decimals).toString()
                .toString()} ${measurementSalesUnitName}) = (${qtyInBaseUnits} ${measurementBaseUnitName})`;
            choiceElem.onclick = (event: Event) => {
                const element = <HTMLSelectElement>event.currentTarget;
                const qtyInBaseUnitsChoice = parseFloat(element.dataset.baseUnitQty);
                const qtyInSalesUnitsChoice = parseFloat(element.dataset.salesUnitQty);
                this.selectQty(qtyInBaseUnitsChoice, qtyInSalesUnitsChoice);
            };

            choiceElem.style.display = 'block';

            return choiceElem;
        }
    }

    protected selectQty(qtyInBaseUnits: number, qtyInSalesUnits: number): void {
        this.qtyInBaseUnitInput.value = qtyInBaseUnits.toString();
        this.qtyInSalesUnitInput.value = this.round(qtyInSalesUnits, this.decimals).toString().toString();
        this.addToCartButton.removeAttribute('disabled');
        this.measurementUnitChoice.classList.add('is-hidden');
    }

    protected getMinChoice(qtyInSalesUnits: number): number {
        const qtyInBaseUnits = this.floor(this.multiply(qtyInSalesUnits, this.currentSalesUnit.conversion));

        if (qtyInBaseUnits < this.getMinQuantity()) {
            return this.getMinQuantity();
        }

        if ((qtyInBaseUnits - this.getMinQuantity()) % this.getQuantityInterval() !== 0 || (this.getMaxQuantity() > 0
            && qtyInBaseUnits > this.getMaxQuantity())) {
            return this.getMinChoice((qtyInBaseUnits - 1) / this.currentSalesUnit.conversion);
        }

        return qtyInBaseUnits;
    }

    protected getMaxChoice(qtyInSalesUnits: number, minChoice: number): number {
        let qtyInBaseUnits = this.ceil(this.multiply(qtyInSalesUnits, this.currentSalesUnit.conversion));

        if (this.getMaxQuantity() > 0 && qtyInBaseUnits > this.getMaxQuantity()) {
            qtyInBaseUnits = this.getMaxQuantity();

            if ((qtyInBaseUnits - this.getMinQuantity()) % this.getQuantityInterval() !== 0) {
                qtyInBaseUnits = qtyInBaseUnits - ((qtyInBaseUnits - this.getMinQuantity()) %
                    this.getQuantityInterval());
            }

            return qtyInBaseUnits;
        }

        if ((qtyInBaseUnits - this.getMinQuantity()) % this.getQuantityInterval() !== 0 ||
            qtyInBaseUnits <= minChoice) {
            return this.getMaxChoice((qtyInBaseUnits + 1) / this.currentSalesUnit.conversion, minChoice);
        }

        return qtyInBaseUnits;
    }

    protected floor(value: number): number {
        if (Math.floor(value) > 0) {
            return Math.floor(value);
        }

        return Math.ceil(value);
    }

    protected ceil(value: number): number {
        return Math.ceil(value);
    }

    protected round(value: number, decimals: number): number {
        return Number(Math.round(parseFloat(value + 'e' + decimals)) + 'e-' + decimals);
    }

    protected multiply(a: number, b: number): number {
        const result = ((a * this.factor) * (b * this.factor)) / Math.pow(this.factor, this.degree[0]);

        return Math.floor(result * Math.pow(this.factor, this.degree[1]) / Math.pow(this.factor, this.degree[1]));
    }

    protected getMinQuantity(): number {
        if (typeof this.productQuantityStorage !== 'undefined'
            && this.productQuantityStorage.hasOwnProperty('quantity_min')
        ) {
            return this.productQuantityStorage.quantity_min;
        }

        return 1;
    }

    protected getMaxQuantity(): number {
        if (typeof this.productQuantityStorage !== 'undefined'
            && this.productQuantityStorage.hasOwnProperty('quantity_max')
            && this.productQuantityStorage.quantity_max !== null
        ) {
            return this.productQuantityStorage.quantity_max;
        }

        return 0;
    }

    protected getQuantityInterval(): number {
        if (typeof this.productQuantityStorage !== 'undefined'
            && this.productQuantityStorage.hasOwnProperty('quantity_interval')) {
            return this.productQuantityStorage.quantity_interval;
        }

        return 1;
    }

    protected measurementUnitInputChange(event: Event): void {
        const salesUnitId = parseInt((<HTMLSelectElement>event.currentTarget).value);
        const salesUnit = this.getSalesUnitById(salesUnitId);
        let qtyInSalesUnits = +this.qtyInSalesUnitInput.value;
        const qtyInBaseUnits = this.multiply(qtyInSalesUnits, this.currentSalesUnit.conversion);
        qtyInSalesUnits = qtyInBaseUnits / salesUnit.conversion;
        this.currentSalesUnit = salesUnit;
        this.qtyInSalesUnitInput.value = this.round(qtyInSalesUnits, this.decimals).toString();
        this.qtyInputChange(qtyInSalesUnits);
    }

    protected getSalesUnitById(salesUnitId: number): SalesUnit {
        const targetSalesUnits = this.salesUnits.filter(
            (item: SalesUnit) => salesUnitId === item.id_product_measurement_sales_unit
        );

        return targetSalesUnits[0];
    }

    protected getBaseSalesUnit(): SalesUnit {
        const targetBaseUnits = this.salesUnits.filter(
            (item: SalesUnit) =>
                this.baseUnit.id_product_measurement_unit === item.product_measurement_unit.id_product_measurement_unit
        );

        return targetBaseUnits[0];
    }

    protected getUnitName(key: string): string {
        if (this.translations.hasOwnProperty(key)) {
            return this.translations[key];
        }

        return key;
    }

    protected get measurementJSONString(): string {
        return this.getAttribute('json');
    }
}
