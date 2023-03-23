/* eslint-disable max-lines */

/*
 * @tag example This code provides example of using the Product Packaging Unit.
 */

import Component from 'ShopUi/models/component';
import { mount } from 'ShopUi/app';
import AutoNumeric from 'autonumeric';
import FormattedNumberInput from 'ShopUi/components/molecules/formatted-number-input/formatted-number-input';

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

const HIDDEN_CLASS_NAME = 'is-hidden';

export default class PackagingUnitQuantitySelector extends Component {
    protected formattedQtyInSalesUnitInput: FormattedNumberInput;
    protected formattedQtyInSalesUnitInputConfig: AutoNumeric.Options;
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
    protected formattedAmountInSalesUnitInput: FormattedNumberInput;
    protected formattedAmountInSalesUnitInputConfig: AutoNumeric.Options;
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
    protected muError = false;
    protected puError = false;
    protected readonly decimals = 4;
    protected readonly factor = 10;
    protected numberOfDecimalPlaces = 10;
    // eslint-disable-next-line @typescript-eslint/no-magic-numbers
    protected readonly degree: number[] = [2, 3];

    protected readyCallback(): void {}

    protected async init(): Promise<void> {
        this.formattedQtyInSalesUnitInput = <FormattedNumberInput>(
            this.getElementsByClassName(`${this.jsName}__formatted-sales-unit-quantity`)[0]
        );
        this.qtyInSalesUnitInput = <HTMLInputElement>(
            this.getElementsByClassName(`${this.jsName}__sales-unit-quantity`)[0]
        );

        if (!this.qtyInSalesUnitInput) {
            return;
        }

        this.qtyInBaseUnitInput = <HTMLInputElement>(
            this.getElementsByClassName(`${this.jsName}__base-unit-quantity`)[0]
        );
        this.measurementUnitInput = <HTMLSelectElement>(
            this.getElementsByClassName(`${this.jsName}__select-measurement-unit`)[0]
        );
        this.addToCartButton = <HTMLButtonElement>this.getElementsByClassName(`${this.jsName}__add-to-cart-button`)[0];
        this.leadSalesUnitSelect = <HTMLSelectElement>(
            this.getElementsByClassName(`${this.jsName}__select-lead-measurement-unit`)[0]
        );
        this.formattedAmountInSalesUnitInput = <FormattedNumberInput>(
            this.getElementsByClassName(`${this.jsName}__formatted-user-amount`)[0]
        );
        this.amountInSalesUnitInput = <HTMLInputElement>this.getElementsByClassName(`${this.jsName}__user-amount`)[0];
        this.amountDefaultInBaseUnitInput = <HTMLInputElement>(
            this.getElementsByClassName(`${this.jsName}__default-amount`)[0]
        );
        this.amountInBaseUnitInput = <HTMLInputElement>document.getElementsByClassName(`${this.jsName}__amount`)[0];
        this.productPackagingNewPriceBlock = <HTMLDivElement>(
            this.getElementsByClassName(`${this.jsName}__product-packaging-new-price-block`)[0]
        );
        this.productPackagingNewPriceValueBlock = <HTMLDivElement>(
            this.getElementsByClassName(`${this.jsName}__product-packaging-new-price-value-block`)[0]
        );
        this.itemBasePriceInput = <HTMLInputElement>this.getElementsByClassName(`${this.jsName}__item-base-price`)[0];
        this.itemMoneySymbolInput = <HTMLInputElement>(
            this.getElementsByClassName(`${this.jsName}__item-money-symbol`)[0]
        );
        this.quantityBetweenElement = <HTMLDivElement>(
            this.getElementsByClassName(`${this.jsName}__quantity-between-units`)[0]
        );
        this.quantityMinElement = <HTMLDivElement>this.getElementsByClassName(`${this.jsName}__minimum-quantity`)[0];
        this.quantityMaxElement = <HTMLDivElement>this.getElementsByClassName(`${this.jsName}__maximum-quantity`)[0];
        this.muChoiceNotificationElement = <HTMLDivElement>(
            this.getElementsByClassName(`${this.jsName}__measurement-unit-choice`)[0]
        );
        this.muChoiceListElement = <HTMLUListElement>(
            this.muChoiceNotificationElement.getElementsByClassName(`${this.jsName}__list`)[0]
        );
        this.muCurrentChoiceElement = <HTMLSpanElement>(
            this.muChoiceNotificationElement.getElementsByClassName(`${this.jsName}__current-choice`)[0]
        );
        this.puChoiceElement = <HTMLDivElement>this.getElementsByClassName(`${this.jsName}__packaging-unit-choice`)[0];
        this.puMinNotificationElement = <HTMLDivElement>(
            this.getElementsByClassName(`${this.jsName}__packaging-amount-min`)[0]
        );
        this.puMaxNotificationElement = <HTMLDivElement>(
            this.getElementsByClassName(`${this.jsName}__packaging-amount-max`)[0]
        );
        this.puIntervalNotificationElement = <HTMLDivElement>(
            this.getElementsByClassName(`${this.jsName}__packaging-amount-interval`)[0]
        );

        this.initJson();
        this.initTranslations();
        this.initCurrentSalesUnit();
        this.initCurrentLeadSalesUnit();
        this.mapEvents();
        await mount();
        this.initFormDefaultValues();
        if (this.amountInBaseUnitInput) {
            this.onAmountInputChange();
        }
        if (this.formattedQtyInSalesUnitInput) {
            this.formattedQtyInSalesUnitInputConfig = this.formattedQtyInSalesUnitInput.numberFormatConfig;
        }
        if (this.formattedAmountInSalesUnitInput) {
            this.formattedAmountInSalesUnitInputConfig = this.formattedAmountInSalesUnitInput.numberFormatConfig;
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

        this.qtyInSalesUnitInput.value = String(this.getMinQuantity());

        if (this.leadSalesUnitSelect) {
            this.leadSalesUnitSelect.value = String(this.currentLeadSalesUnit.id_product_measurement_sales_unit);
        }

        if (this.measurementUnitInput) {
            this.measurementUnitInput.value = String(this.currentSalesUnit.id_product_measurement_sales_unit);
        }
    }

    protected initTranslations(): void {
        this.translations = JSON.parse(
            this.getElementsByClassName(`${this.jsName}__measurement-unit-translation`)[0].innerHTML,
        );
    }

    protected initCurrentSalesUnit(): void {
        this.currentSalesUnit = this.salesUnits.find((item: SalesUnit) => item.is_default);
    }

    protected mapEvents(): void {
        this.qtyInSalesUnitInput.addEventListener('input', () => this.qtyInputChange());

        if (this.measurementUnitInput) {
            this.measurementUnitInput.addEventListener('change', (event: Event) =>
                this.onMeasurementUnitInputChange(event),
            );
        }

        if (this.amountInSalesUnitInput) {
            this.amountInSalesUnitInput.addEventListener('input', () => this.onAmountInputChange());
        }

        if (this.leadSalesUnitSelect) {
            this.leadSalesUnitSelect.addEventListener('change', (event: Event) =>
                this.onLeadSalesUnitSelectChange(event),
            );
        }
    }

    protected qtyInputChange(qtyInSalesUnits?: number): void {
        if (typeof qtyInSalesUnits === 'undefined') {
            qtyInSalesUnits = this.formattedQtyInSalesUnitInput.unformattedValue;
        }

        this.muError = false;
        const qtyInBaseUnits = this.multiply(qtyInSalesUnits, Number(this.currentSalesUnit.conversion));

        if (qtyInBaseUnits < this.getMinQuantity()) {
            this.muError = true;
            this.hideNotifications();
            this.quantityMinElement.classList.remove(HIDDEN_CLASS_NAME);
        } else if ((qtyInBaseUnits - this.getMinQuantity()) % this.getQuantityInterval() !== 0) {
            this.muError = true;
            this.hideNotifications();
            this.quantityBetweenElement.classList.remove(HIDDEN_CLASS_NAME);
        } else if (this.getMaxQuantity() > 0 && qtyInBaseUnits > this.getMaxQuantity()) {
            this.muError = true;
            this.hideNotifications();
            this.quantityMaxElement.classList.remove(HIDDEN_CLASS_NAME);
        }

        if (this.muError && !isFinite(qtyInSalesUnits)) {
            this.addToCartButton.setAttribute('disabled', 'disabled');
            this.qtyInSalesUnitInput.setAttribute('disabled', 'disabled');

            return;
        }

        if (this.muError || this.puError || this.isAddToCartDisabled) {
            this.addToCartButton.setAttribute('disabled', 'disabled');
            this.showCorrectInputSuggestions(qtyInSalesUnits);

            return;
        }

        this.qtyInBaseUnitInput.value = String(qtyInBaseUnits);

        if (this.amountInBaseUnitInput) {
            this.onAmountInputChange();
        }

        this.addToCartButton.removeAttribute('disabled');
        this.hideNotifications();
    }

    protected hideNotifications(): void {
        this.muChoiceNotificationElement.classList.add(HIDDEN_CLASS_NAME);
        this.quantityBetweenElement.classList.add(HIDDEN_CLASS_NAME);
        this.quantityMinElement.classList.add(HIDDEN_CLASS_NAME);
        this.quantityMaxElement.classList.add(HIDDEN_CLASS_NAME);
    }

    protected showCorrectInputSuggestions(qtyInSalesUnits: number): void {
        if (this.muError) {
            const minChoice = this.getMinChoice(qtyInSalesUnits);
            const maxChoice = this.getMaxChoice(qtyInSalesUnits, minChoice);

            this.muChoiceListElement.innerHTML = '';
            this.muCurrentChoiceElement.innerHTML = '';
            this.formatNumber(
                this.muCurrentChoiceElement,
                this.round(qtyInSalesUnits, this.decimals),
                this.formattedQtyInSalesUnitInputConfig,
            );
            this.muCurrentChoiceElement.innerHTML += `&nbsp${this.getUnitName(
                this.currentSalesUnit.product_measurement_unit.code,
            )}`;

            const choiceElements = [];
            choiceElements.push(this.createChoiceElement(minChoice));

            if (maxChoice !== minChoice) {
                choiceElements.push(this.createChoiceElement(maxChoice));
            }

            choiceElements.forEach((element) => {
                if (!element) {
                    return;
                }

                this.muChoiceListElement.appendChild(element);
            });

            this.muChoiceNotificationElement.classList.remove(HIDDEN_CLASS_NAME);
        }
    }

    protected createChoiceElement(qtyInBaseUnits: number): HTMLSpanElement | void {
        if (qtyInBaseUnits < 1) {
            return;
        }

        const choiceElement = document.createElement('button');
        const qtyInSalesUnits = this.convertBaseUnitsAmountToCurrentSalesUnitsAmount(qtyInBaseUnits);
        const measurementSalesUnitName = this.getUnitName(this.currentSalesUnit.product_measurement_unit.code);
        const measurementBaseUnitName = this.getUnitName(this.baseUnit.code);
        const qtyInSalesUnitsElement = document.createElement('span');
        const qtyInBaseUnitsElement = document.createElement('span');

        this.formatNumber(
            qtyInSalesUnitsElement,
            this.round(qtyInSalesUnits, this.decimals),
            this.formattedQtyInSalesUnitInputConfig,
        );
        this.formatNumber(qtyInBaseUnitsElement, qtyInBaseUnits, this.formattedQtyInSalesUnitInputConfig);
        qtyInSalesUnitsElement.innerHTML += `&nbsp${measurementSalesUnitName}`;
        qtyInBaseUnitsElement.innerHTML += `&nbsp${measurementBaseUnitName}`;
        choiceElement.append('(', qtyInSalesUnitsElement, ') = (', qtyInBaseUnitsElement, ')');
        choiceElement.type = 'button';
        choiceElement.classList.add('link', 'link--expand');
        choiceElement.setAttribute('data-base-unit-qty', String(qtyInBaseUnits));
        choiceElement.setAttribute('data-sales-unit-qty', String(qtyInSalesUnits));
        choiceElement.onclick = (event: Event) => {
            const element = <HTMLSelectElement>event.currentTarget;
            const qtyInBaseUnitsValue = parseFloat(element.dataset.baseUnitQty);
            const qtyInSalesUnitsValue = parseFloat(element.dataset.salesUnitQty);
            this.muError = false;
            this.selectQty(qtyInBaseUnitsValue, qtyInSalesUnitsValue);
        };

        return choiceElement;
    }

    protected selectQty(qtyInBaseUnits: number, qtyInSalesUnits: number): void {
        this.qtyInBaseUnitInput.value = String(qtyInBaseUnits);
        this.qtyInSalesUnitInput.value = String(this.round(qtyInSalesUnits, this.decimals));
        if (!this.puError && !this.isAddToCartDisabled) {
            this.addToCartButton.removeAttribute('disabled');
            this.qtyInSalesUnitInput.removeAttribute('disabled');
        }
        this.muChoiceNotificationElement.classList.add(HIDDEN_CLASS_NAME);
        this.qtyInputChange();
    }

    protected getMinChoice(qtyInSalesUnits: number): number {
        const qtyInBaseUnits = this.floor(this.multiply(qtyInSalesUnits, this.currentSalesUnit.conversion));

        if (qtyInBaseUnits < this.getMinQuantity()) {
            return this.getMinQuantity();
        }

        if (
            (qtyInBaseUnits - this.getMinQuantity()) % this.getQuantityInterval() !== 0 ||
            (this.getMaxQuantity() > 0 && qtyInBaseUnits > this.getMaxQuantity())
        ) {
            return this.getMinChoice(this.convertBaseUnitsAmountToCurrentSalesUnitsAmount(qtyInBaseUnits - 1));
        }

        return qtyInBaseUnits;
    }

    protected getMaxChoice(qtyInSalesUnits: number, minChoice: number): number {
        let qtyInBaseUnits = this.ceil(this.multiply(qtyInSalesUnits, this.currentSalesUnit.conversion));

        if (this.getMaxQuantity() > 0 && qtyInBaseUnits > this.getMaxQuantity()) {
            qtyInBaseUnits = this.getMaxQuantity();

            if ((qtyInBaseUnits - this.getMinQuantity()) % this.getQuantityInterval() !== 0) {
                qtyInBaseUnits =
                    qtyInBaseUnits - ((qtyInBaseUnits - this.getMinQuantity()) % this.getQuantityInterval());
            }

            return qtyInBaseUnits;
        }

        if ((qtyInBaseUnits - this.getMinQuantity()) % this.getQuantityInterval() !== 0) {
            return this.getMaxChoice(
                this.convertBaseUnitsAmountToCurrentSalesUnitsAmount(
                    (qtyInBaseUnits + 1) / this.currentSalesUnit.conversion,
                ),
                minChoice,
            );
        }

        return qtyInBaseUnits;
    }

    protected convertBaseUnitsAmountToCurrentSalesUnitsAmount(qtyInBaseUnits: number): number {
        return (
            Math.round((qtyInBaseUnits / this.currentSalesUnit.conversion) * this.currentSalesUnit.precision) /
            this.currentSalesUnit.precision
        );
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
        const result = a * b;
        const precision = Number(this.currentSalesUnit.precision);

        return Math.round(result * precision) / precision;
    }

    protected getMinQuantity(): number {
        if (
            typeof this.productQuantityStorage !== 'undefined' &&
            this.productQuantityStorage.hasOwnProperty('quantity_min')
        ) {
            return this.productQuantityStorage.quantity_min;
        }

        return 1;
    }

    protected getMaxQuantity(): number {
        if (
            typeof this.productQuantityStorage !== 'undefined' &&
            this.productQuantityStorage.hasOwnProperty('quantity_max') &&
            this.productQuantityStorage.quantity_max !== null
        ) {
            return this.productQuantityStorage.quantity_max;
        }

        return 0;
    }

    protected getQuantityInterval(): number {
        if (
            typeof this.productQuantityStorage !== 'undefined' &&
            this.productQuantityStorage.hasOwnProperty('quantity_interval')
        ) {
            return this.productQuantityStorage.quantity_interval;
        }

        return 1;
    }

    protected onMeasurementUnitInputChange(event: Event): void {
        const salesUnitId = parseInt((<HTMLSelectElement>event.currentTarget).value);
        const salesUnit = this.getSalesUnitById(salesUnitId);
        let qtyInSalesUnits = this.formattedQtyInSalesUnitInput.unformattedValue;
        const qtyInBaseUnits = this.multiply(qtyInSalesUnits, this.currentSalesUnit.conversion);
        this.currentSalesUnit = salesUnit;
        qtyInSalesUnits = this.convertBaseUnitsAmountToCurrentSalesUnitsAmount(qtyInBaseUnits);

        if (isFinite(qtyInSalesUnits)) {
            this.qtyInSalesUnitInput.value = String(this.round(qtyInSalesUnits, this.decimals));
        }

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

    protected onAmountInputChange(amountInSalesUnitInput?: number): void {
        if (typeof amountInSalesUnitInput === 'undefined') {
            amountInSalesUnitInput = this.formattedAmountInSalesUnitInput.unformattedValue;
        }

        const amountInBaseUnits = Number(
            (
                (amountInSalesUnitInput * this.precision * Number(this.currentLeadSalesUnit.conversion)) /
                this.precision
            ).toFixed(this.numberOfDecimalPlaces),
        );

        this.productPackagingNewPriceBlock.classList.add(HIDDEN_CLASS_NAME);
        this.puError = false;

        if (!this.amountInSalesUnitInput.disabled) {
            if (this.isAmountMultipleToInterval(amountInBaseUnits)) {
                this.puError = true;
                this.puIntervalNotificationElement.classList.remove(HIDDEN_CLASS_NAME);
            }

            if (amountInBaseUnits < this.getMinAmount()) {
                this.puError = true;
                this.puMinNotificationElement.classList.remove(HIDDEN_CLASS_NAME);
            }

            if (this.getMaxAmount() > 0 && amountInBaseUnits > this.getMaxAmount()) {
                this.puError = true;
                this.puMaxNotificationElement.classList.remove(HIDDEN_CLASS_NAME);
            }
        }

        if (this.puError || this.muError || this.isAddToCartDisabled) {
            this.askCustomerForCorrectAmountInput(amountInSalesUnitInput);
            this.addToCartButton.setAttribute('disabled', 'disabled');

            return undefined;
        }

        const quantity = Number(this.qtyInBaseUnitInput.value);
        const totalAmount = ((amountInBaseUnits * this.precision * quantity) / this.precision).toFixed(
            this.numberOfDecimalPlaces,
        );

        this.amountInBaseUnitInput.value = String(parseFloat(totalAmount));
        this.addToCartButton.removeAttribute('disabled');
        this.hidePackagingUnitRestrictionNotifications();

        this.priceCalculation(amountInBaseUnits);
    }

    protected priceCalculation(amountInBaseUnits: number): void {
        const itemBasePrice = Number(this.itemBasePriceInput.value);
        const amountDefaultInBaseUnitInputValue = Number(this.amountDefaultInBaseUnitInput.value);

        if (amountDefaultInBaseUnitInputValue !== amountInBaseUnits) {
            let newPrice = (amountInBaseUnits / amountDefaultInBaseUnitInputValue) * itemBasePrice;
            newPrice = (newPrice * Number(this.qtyInBaseUnitInput.value)) / Math.pow(this.factor, this.degree[0]);
            this.productPackagingNewPriceValueBlock.innerHTML =
                this.itemMoneySymbolInput.value + newPrice.toFixed(this.degree[0]);
            this.productPackagingNewPriceBlock.classList.remove(HIDDEN_CLASS_NAME);
        }
    }

    protected askCustomerForCorrectAmountInput(amountInSalesUnits: number): void {
        const puChoiceListElement = this.puChoiceElement.getElementsByClassName(`${this.jsName}__list`)[0];
        const puCurrentChoiceElement = <HTMLElement>(
            this.puChoiceElement.getElementsByClassName(`${this.jsName}__amount-current-choice`)[0]
        );

        if (this.puError) {
            const minChoice = this.getMinAmountChoice(amountInSalesUnits);
            const maxChoice = this.getMaxAmountChoice(amountInSalesUnits, minChoice);

            puChoiceListElement.innerHTML = '';
            puCurrentChoiceElement.innerHTML = '';
            this.formatNumber(
                puCurrentChoiceElement,
                this.round(amountInSalesUnits, this.decimals),
                this.formattedAmountInSalesUnitInputConfig,
            );
            puCurrentChoiceElement.innerHTML += `&nbsp${this.getUnitName(
                this.currentSalesUnit.product_measurement_unit.code,
            )}`;

            const choiceElements = [];

            if (minChoice) {
                choiceElements.push(this.createAmountChoiceElement(minChoice));
            }

            if (maxChoice !== minChoice) {
                choiceElements.push(this.createAmountChoiceElement(maxChoice));
            }

            choiceElements.forEach((element) => {
                if (!element) {
                    return;
                }

                puChoiceListElement.appendChild(element);
            });

            this.puChoiceElement.classList.remove(HIDDEN_CLASS_NAME);
        }
    }

    protected initCurrentLeadSalesUnit(): void {
        if (!this.leadSalesUnits) {
            return;
        }

        this.currentLeadSalesUnit = this.leadSalesUnits.find((item: SalesUnit) => item.is_default);
    }

    protected createAmountChoiceElement(amountInBaseUnits: number): HTMLSpanElement | void {
        if (amountInBaseUnits < 1) {
            return;
        }

        const choiceElement = document.createElement('button');
        const amountInSalesUnits = (
            (amountInBaseUnits * this.precision) /
            this.currentLeadSalesUnit.conversion /
            this.precision
        ).toFixed(this.numberOfDecimalPlaces);
        const measurementSalesUnitName = this.getUnitName(this.currentLeadSalesUnit.product_measurement_unit.code);
        const measurementBaseUnitName = this.getUnitName(this.baseUnit.code);
        const salesUnitChoiceElement = document.createElement('span');
        const baseUnitChoiceElement = document.createElement('span');

        this.formatNumber(
            salesUnitChoiceElement,
            parseFloat(amountInSalesUnits),
            this.formattedAmountInSalesUnitInputConfig,
        );
        this.formatNumber(baseUnitChoiceElement, amountInBaseUnits, this.formattedAmountInSalesUnitInputConfig);
        salesUnitChoiceElement.innerHTML += `&nbsp${measurementSalesUnitName}`;
        baseUnitChoiceElement.innerHTML += `&nbsp${measurementBaseUnitName}`;
        choiceElement.append('(', salesUnitChoiceElement, ') = (', baseUnitChoiceElement, ')');
        choiceElement.type = 'button';
        choiceElement.classList.add('link', 'link--expand');
        choiceElement.setAttribute('data-base-unit-amount', String(amountInBaseUnits));
        choiceElement.setAttribute('data-sales-unit-amount', String(parseFloat(amountInSalesUnits)));
        choiceElement.onclick = (event: Event) => {
            const element = <HTMLSelectElement>event.currentTarget;
            const amountInBaseUnitsValue = parseFloat(element.dataset.baseUnitAmount);
            const amountInSalesUnitsValue = parseFloat(element.dataset.salesUnitAmount);
            this.puError = false;
            this.selectAmount(amountInBaseUnitsValue, amountInSalesUnitsValue);
        };

        return choiceElement;
    }

    protected selectAmount(amountInBaseUnits: number, amountInSalesUnits: number): void {
        this.amountInSalesUnitInput.value = String(amountInSalesUnits);
        this.amountInBaseUnitInput.value = String(amountInBaseUnits);
        if (!this.muError && !this.isAddToCartDisabled) {
            this.addToCartButton.removeAttribute('disabled');
        }
        this.puChoiceElement.classList.add(HIDDEN_CLASS_NAME);
        this.onAmountInputChange();
    }

    protected onLeadSalesUnitSelectChange(event: Event): void {
        const salesUnitId = parseInt((<HTMLSelectElement>event.currentTarget).value);
        const salesUnit = this.getLeadSalesUnitById(salesUnitId);

        const amountInSalesUnits = this.getAmountConversion(
            this.formattedAmountInSalesUnitInput.unformattedValue,
            salesUnit.conversion,
        );
        const amountInSalesUnitsMin = this.getAmountConversion(
            Number(this.amountInSalesUnitInput.min),
            salesUnit.conversion,
        );
        const amountInSalesUnitsMax = this.getAmountConversion(
            Number(this.amountInSalesUnitInput.max),
            salesUnit.conversion,
        );
        const amountInSalesUnitsStep = this.getAmountConversion(
            Number(this.amountInSalesUnitInput.step),
            salesUnit.conversion,
        );

        this.currentLeadSalesUnit = salesUnit;
        this.amountInSalesUnitInput.value = String(amountInSalesUnits);

        if (this.amountInSalesUnitInput.min) {
            this.amountInSalesUnitInput.min = String(amountInSalesUnitsMin);
        }

        if (this.amountInSalesUnitInput.max) {
            this.amountInSalesUnitInput.max = String(amountInSalesUnitsMax);
        }

        if (this.amountInSalesUnitInput.step) {
            this.amountInSalesUnitInput.step = String(amountInSalesUnitsStep);
        }

        this.onAmountInputChange(amountInSalesUnits);
    }

    protected hidePackagingUnitRestrictionNotifications(): void {
        this.puChoiceElement.classList.add(HIDDEN_CLASS_NAME);
        this.puMinNotificationElement.classList.add(HIDDEN_CLASS_NAME);
        this.puMaxNotificationElement.classList.add(HIDDEN_CLASS_NAME);
        this.puIntervalNotificationElement.classList.add(HIDDEN_CLASS_NAME);
    }

    protected getLeadSalesUnitById(salesUnitId: number): SalesUnit {
        return this.leadSalesUnits.find((item: SalesUnit) => salesUnitId === item.id_product_measurement_sales_unit);
    }

    protected getMinAmount(): number {
        if (
            typeof this.productPackagingUnitStorage !== 'undefined' &&
            this.productPackagingUnitStorage.hasOwnProperty('amount_min') &&
            this.productPackagingUnitStorage.amount_min !== null
        ) {
            return Number(this.productPackagingUnitStorage.amount_min);
        }

        return 1;
    }

    protected getMaxAmount(): number {
        if (
            typeof this.productPackagingUnitStorage !== 'undefined' &&
            this.productPackagingUnitStorage.hasOwnProperty('amount_max') &&
            this.productPackagingUnitStorage.amount_max !== null
        ) {
            return Number(this.productPackagingUnitStorage.amount_max);
        }

        return 0;
    }

    protected getAmountInterval(): number {
        if (
            typeof this.productPackagingUnitStorage !== 'undefined' &&
            this.productPackagingUnitStorage.hasOwnProperty('amount_interval') &&
            this.productPackagingUnitStorage.amount_interval !== null
        ) {
            return Number(this.productPackagingUnitStorage.amount_interval);
        }

        return 1;
    }

    protected getMinAmountChoice(amountInSalesUnits: number): number {
        const amountInBaseUnits = Number(
            (
                (amountInSalesUnits * this.precision * Number(this.currentLeadSalesUnit.conversion)) /
                this.precision
            ).toFixed(this.numberOfDecimalPlaces),
        );

        if (amountInBaseUnits < this.getMinAmount()) {
            return this.getMinAmount();
        }

        if (this.isAmountGreaterThanMaxAmount(amountInBaseUnits)) {
            return 0;
        }

        if (this.isAmountMultipleToInterval(amountInBaseUnits)) {
            return this.getMinAmountChoice(
                (amountInBaseUnits - this.getAmountPercentageOfDivision(amountInBaseUnits)) /
                    this.currentLeadSalesUnit.conversion,
            );
        }

        return amountInBaseUnits;
    }

    protected getMaxAmountChoice(amountInSalesUnits: number, minChoice: number): number {
        let amountInBaseUnits = Number(
            (
                (amountInSalesUnits * this.precision * Number(this.currentLeadSalesUnit.conversion)) /
                this.precision
            ).toFixed(this.numberOfDecimalPlaces),
        );

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
            const nextPossibleInterval = Number(
                ((minChoice * this.precision + this.getAmountInterval() * this.precision) / this.precision).toFixed(
                    this.numberOfDecimalPlaces,
                ),
            );

            return nextPossibleInterval;
        }

        return amountInBaseUnits;
    }

    protected formatNumber(target: HTMLElement, value: number, config?: AutoNumeric.Options): AutoNumeric {
        return new AutoNumeric(target, value, config);
    }

    protected isAmountGreaterThanMaxAmount(amountInBaseUnits: number): boolean {
        return this.getMaxAmount() > 0 && amountInBaseUnits > this.getMaxAmount();
    }

    protected isAmountMultipleToInterval(amountInBaseUnits: number): boolean {
        return this.getAmountPercentageOfDivision(amountInBaseUnits) !== 0;
    }

    protected getAmountConversion(value: number, conversion: number): number {
        return parseFloat(
            ((value * this.precision * this.currentLeadSalesUnit.conversion) / conversion / this.precision).toFixed(
                this.numberOfDecimalPlaces,
            ),
        );
    }

    protected getAmountPercentageOfDivision(amountInBaseUnits: number): number {
        const amountMultiplyToPrecision = Math.round(amountInBaseUnits * this.precision);
        const minAmountMultiplyToPrecision = Math.round(this.getMinAmount() * this.precision);
        const amountIntervalMultiplyToPrecision = this.getAmountInterval() * this.precision;
        const currentMinusMinimumAmount = Number(
            ((amountMultiplyToPrecision - minAmountMultiplyToPrecision) / this.precision).toFixed(
                this.numberOfDecimalPlaces,
            ),
        );
        const currentMinusMinimumAmountMultiplyToPrecision = Math.round(currentMinusMinimumAmount * this.precision);
        const amountPercentageOfDivision = (
            (currentMinusMinimumAmountMultiplyToPrecision % amountIntervalMultiplyToPrecision) /
            this.precision
        ).toFixed(this.numberOfDecimalPlaces);

        return Number(amountPercentageOfDivision);
    }

    protected get precision(): number {
        return Number(`1${'0'.repeat(this.numberOfDecimalPlaces)}`);
    }

    protected get packagingJSONString(): string {
        return this.getAttribute('json');
    }
}
