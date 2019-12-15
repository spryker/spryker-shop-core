/* tslint:disable: max-file-line-count */

/*
 * @tag example This code provides example of using the Product Packaging Unit.
 */

import Component from 'ShopUi/models/component';

interface UnitTranslationsJSONData {
    gram: string;
    item: string;
    kilo: string;
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

interface ProductPackagingUnitStorage {
    amount_interval: string;
    amount_max: string;
    amount_min: string;
    default_amount: string;
    id_lead_product: number;
    id_product: number;
    is_amount_variable: boolean;
    type_name: string;
}

interface PackagingJSONData {
    baseUnit: BaseUnit;
    salesUnits: SalesUnit[];
    productQuantityStorage: ProductQuantityStorage;
    isAddToCartDisabled: boolean;
    leadSalesUnits: SalesUnit[];
    productPackagingUnitStorage: ProductPackagingUnitStorage;
}

export default class PackagingUnitQuantitySelector extends Component {
    protected qtyInSalesUnitInput: HTMLInputElement;
    protected qtyInBaseUnitInput: HTMLInputElement;
    protected measurementUnitInput: HTMLSelectElement;
    protected addToCartButton: HTMLButtonElement;
    protected leadSalesUnitSelect: HTMLSelectElement;
    protected baseUnit: BaseUnit;
    protected salesUnits: SalesUnit[];
    protected currentSalesUnit: SalesUnit;
    protected productQuantityStorage: ProductQuantityStorage;
    protected translations: UnitTranslationsJSONData;
    protected leadSalesUnits: SalesUnit[];
    protected productPackagingUnitStorage: ProductPackagingUnitStorage;
    protected amountInSalesUnitInput: HTMLInputElement;
    protected amountDefaultInBaseUnitInput: HTMLInputElement;
    protected itemBasePriceInput: HTMLInputElement;
    protected itemMoneySymbolInput: HTMLInputElement;
    protected amountInBaseUnitInput: HTMLInputElement;
    protected currentLeadSalesUnit: SalesUnit;
    protected productPackagingNewPriceBlock: HTMLDivElement;
    protected productPackagingNewPriceValueBlock: HTMLDivElement;
    protected quantityBetweenElement: HTMLDivElement;
    protected quantityMinElement: HTMLDivElement;
    protected quantityMaxElement: HTMLDivElement;
    protected muChoiceNotificationElement: HTMLDivElement;
    protected muChoiceListElement: HTMLUListElement;
    protected muCurrentChoiceElement: HTMLSpanElement;
    protected puChoiceElement: HTMLDivElement;
    protected puMinNotificationElement: HTMLDivElement;
    protected puMaxNotificationElement: HTMLDivElement;
    protected puIntervalNotificationElement: HTMLDivElement;
    protected isAddToCartDisabled: boolean;
    protected muError: boolean = false;
    protected puError: boolean = false;
    protected readonly decimals: number = 4;
    protected readonly factor: number = 10;
    protected numberOfDecimalPlaces: number = 10;

    /* tslint:disable: no-magic-numbers */
    protected readonly degree: number[] = [2, 3];

    /* tslint:enable: no-magic-numbers */

    protected readyCallback(event?: Event): void {}

    protected init(): void {
        this.qtyInSalesUnitInput = <HTMLInputElement>
            this.getElementsByClassName(`${this.jsName}__sales-unit-quantity`)[0];

        if (!this.qtyInSalesUnitInput) {
            return;
        }

        this.qtyInBaseUnitInput = <HTMLInputElement>
            this.getElementsByClassName(`${this.jsName}__base-unit-quantity`)[0];
        this.measurementUnitInput = <HTMLSelectElement>this.getElementsByClassName(
            `${this.jsName}__select-measurement-unit`)[0];
        this.addToCartButton = <HTMLButtonElement>this.getElementsByClassName(
            `${this.jsName}__add-to-cart-button`)[0];
        this.leadSalesUnitSelect = <HTMLSelectElement>this.getElementsByClassName(
            `${this.jsName}__select-lead-measurement-unit`)[0];
        this.amountInSalesUnitInput = <HTMLInputElement>this.getElementsByClassName(
            `${this.jsName}__user-amount`)[0];
        this.amountDefaultInBaseUnitInput = <HTMLInputElement>this.getElementsByClassName(
            `${this.jsName}__default-amount`)[0];
        this.amountInBaseUnitInput = <HTMLInputElement>document.getElementsByClassName(
            `${this.jsName}__amount`)[0];
        this.productPackagingNewPriceBlock = <HTMLDivElement>this.getElementsByClassName(
            `${this.jsName}__product-packaging-new-price-block`)[0];
        this.productPackagingNewPriceValueBlock = <HTMLDivElement>this.getElementsByClassName(
            `${this.jsName}__product-packaging-new-price-value-block`)[0];
        this.itemBasePriceInput = <HTMLInputElement>this.getElementsByClassName(
            `${this.jsName}__item-base-price`)[0];
        this.itemMoneySymbolInput = <HTMLInputElement>this.getElementsByClassName(
            `${this.jsName}__item-money-symbol`)[0];
        this.quantityBetweenElement = <HTMLDivElement>this.getElementsByClassName(
            `${this.jsName}__quantity-between-units`)[0];
        this.quantityMinElement = <HTMLDivElement>this.getElementsByClassName(
            `${this.jsName}__minimum-quantity`)[0];
        this.quantityMaxElement = <HTMLDivElement>this.getElementsByClassName(
            `${this.jsName}__maximum-quantity`)[0];
        this.muChoiceNotificationElement = <HTMLDivElement>this.getElementsByClassName(
            `${this.jsName}__measurement-unit-choice`)[0];
        this.muChoiceListElement = <HTMLUListElement>this.muChoiceNotificationElement.getElementsByClassName(
            `${this.jsName}__list`)[0];
        this.muCurrentChoiceElement = <HTMLSpanElement>this.muChoiceNotificationElement.getElementsByClassName(
            `${this.jsName}__current-choice`)[0];
        this.puChoiceElement = <HTMLDivElement>this.getElementsByClassName(
            `${this.jsName}__packaging-unit-choice`)[0];
        this.puMinNotificationElement = <HTMLDivElement>this.getElementsByClassName(
            `${this.jsName}__packaging-amount-min`)[0];
        this.puMaxNotificationElement = <HTMLDivElement>this.getElementsByClassName(
            `${this.jsName}__packaging-amount-max`)[0];
        this.puIntervalNotificationElement = <HTMLDivElement>this.getElementsByClassName(
            `${this.jsName}__packaging-amount-interval`)[0];

        this.initJson();
        this.initTranslations();
        this.initCurrentSalesUnit();
        this.initCurrentLeadSalesUnit();
        this.initFormDefaultValues();
        this.mapEvents();
        if (this.amountInBaseUnitInput) {
            this.amountInputChange();
        }
    }

    protected initJson(): void {
        const packagingUnitData = <PackagingJSONData>JSON.parse(this.packagingJSONString);

        this.baseUnit = packagingUnitData.baseUnit;
        this.salesUnits = packagingUnitData.salesUnits;
        this.leadSalesUnits = packagingUnitData.leadSalesUnits;
        this.isAddToCartDisabled = packagingUnitData.isAddToCartDisabled;
        this.productPackagingUnitStorage = packagingUnitData.productPackagingUnitStorage;
        this.productQuantityStorage = packagingUnitData.productQuantityStorage;
    }

    protected initFormDefaultValues(): void {
        if (!this.amountInBaseUnitInput) {
            return;
        }

        this.qtyInSalesUnitInput.value = this.getMinQuantity().toString();

        if (this.leadSalesUnitSelect) {
            this.leadSalesUnitSelect.value = this.currentLeadSalesUnit.id_product_measurement_sales_unit.toString();
        }

        if (this.measurementUnitInput) {
            this.measurementUnitInput.value = this.currentSalesUnit.id_product_measurement_sales_unit.toString();
        }
    }

    protected initTranslations(): void {
        this.translations = JSON.parse(
            this.getElementsByClassName(`${this.jsName}__measurement-unit-translation`)[0].innerHTML
        );
    }

    protected initCurrentSalesUnit(): void {
        this.salesUnits.forEach((item: SalesUnit) => {
            if (item.is_default) {
                this.currentSalesUnit = item;
            }
        });
    }

    protected mapEvents(): void {
        this.qtyInSalesUnitInput.addEventListener('input', (event: Event) => this.qtyInputChange());

        if (this.measurementUnitInput) {
            this.measurementUnitInput.addEventListener('change',
                (event: Event) => this.measurementUnitInputChange(event));
        }

        this.amountInSalesUnitInput.addEventListener('input',
            (event: Event) => this.amountInputChange());

        if (this.leadSalesUnitSelect) {
            this.leadSalesUnitSelect.addEventListener(
                'change', (event: Event) => this.leadSalesUnitSelectChange(event));
        }
    }

    protected qtyInputChange(qtyInSalesUnits?: number): void {
        if (typeof qtyInSalesUnits === 'undefined') {
            qtyInSalesUnits = Number(this.qtyInSalesUnitInput.value);
        }

        this.muError = false;
        const qtyInBaseUnits = this.multiply(qtyInSalesUnits, Number(this.currentSalesUnit.conversion));

        if (qtyInBaseUnits < this.getMinQuantity()) {
            this.muError = true;
            this.hideNotifications();
            this.quantityMinElement.classList.remove('is-hidden');
        } else if ((qtyInBaseUnits - this.getMinQuantity()) % this.getQuantityInterval() !== 0) {
            this.muError = true;
            this.hideNotifications();
            this.quantityBetweenElement.classList.remove('is-hidden');
        } else if (this.getMaxQuantity() > 0 && qtyInBaseUnits > this.getMaxQuantity()) {
            this.muError = true;
            this.hideNotifications();
            this.quantityMaxElement.classList.remove('is-hidden');
        }

        if (this.muError || this.puError || this.isAddToCartDisabled) {
            this.addToCartButton.setAttribute('disabled', 'disabled');
            this.askCustomerForCorrectInput(qtyInSalesUnits);

            return;
        }

        this.qtyInBaseUnitInput.value = qtyInBaseUnits.toString();

        if (this.amountInBaseUnitInput) {
            this.amountInputChange();
        }

        this.addToCartButton.removeAttribute('disabled');
        this.hideNotifications();
    }

    protected hideNotifications(): void {
        this.muChoiceNotificationElement.classList.add('is-hidden');
        this.quantityBetweenElement.classList.add('is-hidden');
        this.quantityMinElement.classList.add('is-hidden');
        this.quantityMaxElement.classList.add('is-hidden');
    }

    protected askCustomerForCorrectInput(qtyInSalesUnits: number): void {
        if (this.muError) {
            const minChoice = this.getMinChoice(qtyInSalesUnits);
            const maxChoice = this.getMaxChoice(qtyInSalesUnits, minChoice);

            this.muChoiceListElement.innerHTML = '';
            this.muCurrentChoiceElement.innerHTML = '';
            this.muCurrentChoiceElement.textContent = `${this.round(qtyInSalesUnits, this.decimals)} ${this.getUnitName(
                this.currentSalesUnit.product_measurement_unit.code)}`;

            const choiceElements = [];
            choiceElements.push(this.createChoiceElement(minChoice));

            if (maxChoice !== minChoice) {
                choiceElements.push(this.createChoiceElement(maxChoice));
            }

            choiceElements.forEach(element =>
                (element !== undefined) ? this.muChoiceListElement.appendChild(element) : undefined);

            this.muChoiceNotificationElement.classList.remove('is-hidden');
        }
    }

    private createChoiceElement(qtyInBaseUnits: number) {
        if (qtyInBaseUnits > 0) {
            const choiceElem = document.createElement('span');
            const qtyInSalesUnits = qtyInBaseUnits / this.currentSalesUnit.conversion;
            const measurementSalesUnitName = this.getUnitName(this.currentSalesUnit.product_measurement_unit.code);
            const measurementBaseUnitName = this.getUnitName(this.baseUnit.code);

            choiceElem.classList.add('link');
            choiceElem.setAttribute('data-base-unit-qty', qtyInBaseUnits.toString());
            choiceElem.setAttribute('data-sales-unit-qty', qtyInSalesUnits.toString());
            choiceElem.textContent = `(${this.round(qtyInSalesUnits, this.decimals)
                .toString().toString()} ${measurementSalesUnitName}) = (${qtyInBaseUnits} ${measurementBaseUnitName})`;
            choiceElem.onclick = (event: Event) => {
                const element = <HTMLSelectElement>event.currentTarget;
                const qtyInBaseUnitsValue = parseFloat(element.dataset.baseUnitQty);
                const qtyInSalesUnitsValue = parseFloat(element.dataset.salesUnitQty);
                this.muError = false;
                this.selectQty(qtyInBaseUnitsValue, qtyInSalesUnitsValue);
            };

            choiceElem.style.display = 'block';

            return choiceElem;
        }

        return undefined;
    }

    protected selectQty(qtyInBaseUnits: number, qtyInSalesUnits: number): void {
        this.qtyInBaseUnitInput.value = qtyInBaseUnits.toString();
        this.qtyInSalesUnitInput.value = this.round(qtyInSalesUnits, this.decimals).toString().toString();
        if (!this.puError && !this.isAddToCartDisabled) {
            this.addToCartButton.removeAttribute('disabled');
        }
        this.muChoiceNotificationElement.classList.add('is-hidden');
        this.qtyInputChange();
    }

    protected getMinChoice(qtyInSalesUnits: number): number {
        const qtyInBaseUnits = this.floor(this.multiply(qtyInSalesUnits, this.currentSalesUnit.conversion));

        if (qtyInBaseUnits < this.getMinQuantity()) {
            return this.getMinQuantity();
        }

        if ((qtyInBaseUnits - this.getMinQuantity()) % this.getQuantityInterval() !== 0 ||
            (this.getMaxQuantity() > 0 && qtyInBaseUnits > this.getMaxQuantity())) {
            return this.getMinChoice((qtyInBaseUnits - 1) / this.currentSalesUnit.conversion);
        }

        return qtyInBaseUnits;
    }

    protected getMaxChoice(qtyInSalesUnits: number, minChoice: number): number {
        let qtyInBaseUnits = this.ceil(this.multiply(qtyInSalesUnits, this.currentSalesUnit.conversion));

        if (this.getMaxQuantity() > 0 && qtyInBaseUnits > this.getMaxQuantity()) {
            qtyInBaseUnits = this.getMaxQuantity();

            if ((qtyInBaseUnits - this.getMinQuantity()) % this.getQuantityInterval() !== 0) {
                qtyInBaseUnits = qtyInBaseUnits - ((qtyInBaseUnits - this.getMinQuantity())
                    % this.getQuantityInterval());
            }

            return qtyInBaseUnits;
        }

        if ((qtyInBaseUnits - this.getMinQuantity()) % this.getQuantityInterval() !== 0) {
            return this.getMaxChoice((
                qtyInBaseUnits + 1) / this.currentSalesUnit.conversion, minChoice
            );
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
        return Number(`${Math.round(parseFloat(`${value}e${decimals}`))}e-${decimals}`);
    }

    protected multiply(a: number, b: number): number {
        const result = ((a * this.factor) * (b * this.factor)) / Math.pow(this.factor, this.degree[0]);

        return Math.floor(result * Math.pow(this.factor, this.degree[1])) / Math.pow(this.factor, this.degree[1]);
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
            && this.productQuantityStorage.hasOwnProperty('quantity_interval')
        ) {
            return this.productQuantityStorage.quantity_interval;
        }

        return 1;
    }

    protected measurementUnitInputChange(event: Event): void {
        const salesUnitId = parseInt((<HTMLSelectElement>event.currentTarget).value);
        const salesUnit = this.getSalesUnitById(salesUnitId);
        let qtyInSalesUnits = Number(this.qtyInSalesUnitInput.value);
        const qtyInBaseUnits = this.multiply(qtyInSalesUnits, this.currentSalesUnit.conversion);
        qtyInSalesUnits = qtyInBaseUnits / salesUnit.conversion;
        this.currentSalesUnit = salesUnit;
        this.qtyInSalesUnitInput.value = this.round(qtyInSalesUnits, this.decimals).toString();
        this.qtyInputChange(qtyInSalesUnits);
    }

    protected getSalesUnitById(salesUnitId: number) {
        return this.salesUnits.find((item: SalesUnit) => salesUnitId === item.id_product_measurement_sales_unit);
    }

    protected getUnitName(key: string) {
        if (this.translations.hasOwnProperty(key)) {
            return this.translations[key];
        }

        return key;
    }

    protected amountInputChange(amountInSalesUnitInput?: number): number {
        const amountDecimalsMaxLength = new RegExp(`((\.|\,)\\d{${this.numberOfDecimalPlaces}})\\d+`, 'g');

        if (this.amountInSalesUnitInput.value.match(/[,.]/)) {
            this.amountInSalesUnitInput.value = this.amountInSalesUnitInput.value.replace(amountDecimalsMaxLength, '$1');
        }

        if (typeof amountInSalesUnitInput === 'undefined') {
            amountInSalesUnitInput = Number(this.amountInSalesUnitInput.value);
        }

        const amountInBaseUnits = Number((((amountInSalesUnitInput * this.precision) * Number(this.currentLeadSalesUnit.conversion)) / this.precision).toFixed(this.numberOfDecimalPlaces));

        this.productPackagingNewPriceBlock.classList.add('is-hidden');
        this.puError = false;

        if (!this.amountInSalesUnitInput.disabled) {
            if (this.isAmountMultipleToInterval(amountInBaseUnits)) {
                this.puError = true;
                this.puIntervalNotificationElement.classList.remove('is-hidden');
            }

            if (amountInBaseUnits < this.getMinAmount()) {
                this.puError = true;
                this.puMinNotificationElement.classList.remove('is-hidden');
            }

            if (this.getMaxAmount() > 0 && amountInBaseUnits > this.getMaxAmount()) {
                this.puError = true;
                this.puMaxNotificationElement.classList.remove('is-hidden');
            }
        }

        if (this.puError || this.muError || this.isAddToCartDisabled) {
            this.askCustomerForCorrectAmountInput(amountInSalesUnitInput);
            this.addToCartButton.setAttribute('disabled', 'disabled');

            return undefined;
        }

        const quantity = Number(this.qtyInBaseUnitInput.value);
        const totalAmount = (((amountInBaseUnits * this.precision) * quantity) / this.precision).toFixed(this.numberOfDecimalPlaces);

        this.amountInBaseUnitInput.value = parseFloat(totalAmount).toString();
        this.addToCartButton.removeAttribute('disabled');
        this.hidePackagingUnitRestrictionNotifications();

        this.priceCalculation(amountInBaseUnits);
    }

    protected priceCalculation(amountInBaseUnits: number): void {
        const itemBasePrice: number = Number(this.itemBasePriceInput.value);
        const amountDefaultInBaseUnitInputValue: number = Number(this.amountDefaultInBaseUnitInput.value);

        if (amountDefaultInBaseUnitInputValue !== amountInBaseUnits) {
            let newPrice = (amountInBaseUnits / amountDefaultInBaseUnitInputValue) * itemBasePrice;
            newPrice = (newPrice * Number(this.qtyInBaseUnitInput.value)) / Math.pow(this.factor, this.degree[0]);
            this.productPackagingNewPriceValueBlock.innerHTML =
                this.itemMoneySymbolInput.value + newPrice.toFixed(this.degree[0]);
            this.productPackagingNewPriceBlock.classList.remove('is-hidden');
        }
    }

    protected askCustomerForCorrectAmountInput(amountInSalesUnits: number): void {
        const puChoiceListElement = this.puChoiceElement.getElementsByClassName(
            `${this.jsName}__list`
        )[0];
        const puCurrentChoiceElement = this.puChoiceElement.getElementsByClassName(
            `${this.jsName}__amount-current-choice`
        )[0];

        if (this.puError) {
            const minChoice = this.getMinAmountChoice(amountInSalesUnits);
            const maxChoice = this.getMaxAmountChoice(amountInSalesUnits, minChoice);

            puChoiceListElement.innerHTML = '';
            puCurrentChoiceElement.innerHTML = '';
            puCurrentChoiceElement.textContent =
                `${this.round(amountInSalesUnits, this.decimals)} ${this.getUnitName(
                    this.currentLeadSalesUnit.product_measurement_unit.code
                )}`;

            const choiceElements = [];

            if (minChoice) {
                choiceElements.push(this.createAmountChoiceElement(minChoice));
            }

            if (maxChoice !== minChoice) {
                choiceElements.push(this.createAmountChoiceElement(maxChoice));
            }

            choiceElements.forEach(element => (element !== undefined) ?
                puChoiceListElement.appendChild(element) : undefined);

            this.puChoiceElement.classList.remove('is-hidden');
        }
    }

    protected initCurrentLeadSalesUnit(): void {
        this.leadSalesUnits.forEach((item: SalesUnit) => {
            if (item.is_default) {
                this.currentLeadSalesUnit = item;
            }
        });
    }

    private createAmountChoiceElement(amountInBaseUnits: number): HTMLSpanElement {
        if (amountInBaseUnits > 0) {
            const choiceElem = document.createElement('span');
            const amountInSalesUnits = (((amountInBaseUnits * this.precision) / this.currentLeadSalesUnit.conversion) / this.precision).toFixed(this.numberOfDecimalPlaces);
            const measurementSalesUnitName = this.getUnitName(this.currentLeadSalesUnit.product_measurement_unit.code);
            const measurementBaseUnitName = this.getUnitName(this.baseUnit.code);

            const salesUnitChoie = `${parseFloat(amountInSalesUnits)} ${measurementSalesUnitName}`;
            const baseUnitChoie = `${amountInBaseUnits} ${measurementBaseUnitName}`;

            choiceElem.classList.add('link');
            choiceElem.setAttribute('data-base-unit-amount', amountInBaseUnits.toString());
            choiceElem.setAttribute('data-sales-unit-amount', parseFloat(amountInSalesUnits).toString());
            choiceElem.textContent = `(${salesUnitChoie}) = (${baseUnitChoie})`;
            choiceElem.onclick = (event: Event) => {
                const element = <HTMLSelectElement>event.currentTarget;
                const amountInBaseUnitsValue = parseFloat(element.dataset.baseUnitAmount);
                const amountInSalesUnitsValue = parseFloat(element.dataset.salesUnitAmount);
                this.puError = false;
                this.selectAmount(amountInBaseUnitsValue, amountInSalesUnitsValue);
            };

            choiceElem.style.display = 'block';

            return choiceElem;
        }
    }

    protected selectAmount(amountInBaseUnits: number, amountInSalesUnits: number): void {
        this.amountInSalesUnitInput.value = amountInSalesUnits.toString();
        this.amountInBaseUnitInput.value = amountInBaseUnits.toString();
        if (!this.muError && !this.isAddToCartDisabled) {
            this.addToCartButton.removeAttribute('disabled');
        }
        this.puChoiceElement.classList.add('is-hidden');
        this.amountInputChange();
    }

    protected leadSalesUnitSelectChange(event: Event): void {
        const salesUnitId = parseInt((<HTMLSelectElement>event.currentTarget).value);
        const salesUnit = this.getLeadSalesUnitById(salesUnitId);

        const amountInSalesUnits = this.getAmountConversion(this.amountInSalesUnitInput.value, salesUnit.conversion);
        const amountInSalesUnitsMin = this.getAmountConversion(this.amountInSalesUnitInput.min, salesUnit.conversion);
        const amountInSalesUnitsMax = this.getAmountConversion(this.amountInSalesUnitInput.max, salesUnit.conversion);
        const amountInSalesUnitsStep = this.getAmountConversion(this.amountInSalesUnitInput.step, salesUnit.conversion);

        this.currentLeadSalesUnit = salesUnit;
        this.amountInSalesUnitInput.value = amountInSalesUnits.toString();

        if (this.amountInSalesUnitInput.min) {
            this.amountInSalesUnitInput.min = amountInSalesUnitsMin.toString();
        }

        if (this.amountInSalesUnitInput.max) {
            this.amountInSalesUnitInput.max = amountInSalesUnitsMax.toString();
        }

        if (this.amountInSalesUnitInput.step) {
            this.amountInSalesUnitInput.step = amountInSalesUnitsStep.toString();
        }

        this.amountInputChange(amountInSalesUnits);
    }

    protected hidePackagingUnitRestrictionNotifications(): void {
        this.puChoiceElement.classList.add('is-hidden');
        this.puMinNotificationElement.classList.add('is-hidden');
        this.puMaxNotificationElement.classList.add('is-hidden');
        this.puIntervalNotificationElement.classList.add('is-hidden');
    }

    protected getLeadSalesUnitById(salesUnitId: number): SalesUnit {
        return this.leadSalesUnits.find((item: SalesUnit) => salesUnitId === item.id_product_measurement_sales_unit);
    }

    protected getMinAmount(): number {
        if (typeof this.productPackagingUnitStorage !== 'undefined'
            && this.productPackagingUnitStorage.hasOwnProperty('amount_min')
            && this.productPackagingUnitStorage.amount_min !== null
        ) {
            return Number(this.productPackagingUnitStorage.amount_min);
        }

        return 1;
    }

    protected getMaxAmount(): number {
        if (typeof this.productPackagingUnitStorage !== 'undefined'
            && this.productPackagingUnitStorage.hasOwnProperty('amount_max')
            && this.productPackagingUnitStorage.amount_max !== null
        ) {
            return Number(this.productPackagingUnitStorage.amount_max);
        }

        return 0;
    }

    protected getAmountInterval(): number {
        if (typeof this.productPackagingUnitStorage !== 'undefined'
            && this.productPackagingUnitStorage.hasOwnProperty('amount_interval')
            && this.productPackagingUnitStorage.amount_interval !== null
        ) {
            return Number(this.productPackagingUnitStorage.amount_interval);
        }

        return 1;
    }

    protected getMinAmountChoice(amountInSalesUnits: number): number {
        const amountInBaseUnits = Number((((amountInSalesUnits * this.precision) * Number(this.currentLeadSalesUnit.conversion)) / this.precision).toFixed(this.numberOfDecimalPlaces));

        if (amountInBaseUnits < this.getMinAmount()) {
            return this.getMinAmount();
        }

        if (this.isAmountGreaterThanMaxAmount(amountInBaseUnits)) {
            return 0;
        }

        if (this.isAmountMultipleToInterval(amountInBaseUnits)) {
            return this.getMinAmountChoice((
                amountInBaseUnits - this.getAmountPercentageOfDivision(amountInBaseUnits)) /
                this.currentLeadSalesUnit.conversion);
        }

        return amountInBaseUnits;
    }

    protected getMaxAmountChoice(amountInSalesUnits: number, minChoice: number): number {
        const amountInBaseUnits = Number((((amountInSalesUnits * this.precision) * Number(this.currentLeadSalesUnit.conversion)) / this.precision).toFixed(this.numberOfDecimalPlaces));

        if (this.isAmountGreaterThanMaxAmount(amountInBaseUnits)) {
            amountInBaseUnits = this.getMaxAmount();

            if (this.isAmountMultipleToInterval(amountInBaseUnits)) {
                amountInBaseUnits = amountInBaseUnits - this.getAmountPercentageOfDivision(amountInBaseUnits);
            }

            return amountInBaseUnits;
        }

        if (amountInBaseUnits <= minChoice) {
            return 0;
        }

        if (this.isAmountMultipleToInterval(amountInBaseUnits)) {
            const nextPossibleInterval = Number((((minChoice * this.precision) + (this.getAmountInterval() * this.precision)) / this.precision).toFixed(this.numberOfDecimalPlaces));

            return nextPossibleInterval;
        }

        return amountInBaseUnits;
    }

    protected isAmountGreaterThanMaxAmount(amountInBaseUnits: number): boolean {
        return this.getMaxAmount() > 0 && amountInBaseUnits > this.getMaxAmount();
    }

    protected isAmountMultipleToInterval(amountInBaseUnits: number): boolean {
        return this.getAmountPercentageOfDivision(amountInBaseUnits) !== 0;
    }

    protected getAmountConversion(value: number, conversion: number): number {
        return parseFloat(((((value * this.precision) * this.currentLeadSalesUnit.conversion) / conversion) / this.precision).toFixed(this.numberOfDecimalPlaces));
    }

    protected getAmountPercentageOfDivision(amountInBaseUnits: number): number {
        const amountMultiplyToPrecision = Math.round((amountInBaseUnits * this.precision));
        const minAmountMultiplyToPrecision = Math.round((this.getMinAmount() * this.precision));
        const amountIntervalMultiplyToPrecision = this.getAmountInterval() * this.precision;
        const currentMinusMinimumAmount = Number(((amountMultiplyToPrecision - minAmountMultiplyToPrecision) / this.precision).toFixed(this.numberOfDecimalPlaces));
        const currentMinusMinimumAmountMultiplyToPrecision = Math.round(
            currentMinusMinimumAmount * this.precision);
        const amountPercentageOfDivision = ((currentMinusMinimumAmountMultiplyToPrecision % amountIntervalMultiplyToPrecision) / this.precision).toFixed(this.numberOfDecimalPlaces);

        return Number(amountPercentageOfDivision);
    }

    protected get precision(): number {
        return Number(`1${'0'.repeat(this.numberOfDecimalPlaces)}`);
    }

    protected get packagingJSONString(): string {
        return this.getAttribute('json');
    }
}
