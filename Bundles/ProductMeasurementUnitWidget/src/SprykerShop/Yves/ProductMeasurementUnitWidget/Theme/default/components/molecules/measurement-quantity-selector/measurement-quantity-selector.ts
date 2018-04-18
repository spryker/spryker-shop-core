import Component from 'shop-ui/models/component';

export default class MeasurementQuantitySelector extends Component {
    qtyInSalesUnitInput: HTMLInputElement;
    qtyInBaseUnitInput: HTMLInputElement;
    measurementUnitInput: HTMLSelectElement;
    addToCartButton: HTMLButtonElement;

    baseUnit: any;
    salesUnits: any;
    currentSalesUnit: any;
    productQuantityStorage: any;
    currentValue: Number;
    translations: any;


    readyCallback(event?: Event): void {
        this.qtyInSalesUnitInput = <HTMLInputElement>document.querySelector('#sales-unit-quantity');
        this.qtyInBaseUnitInput = <HTMLInputElement>document.querySelector('#base-unit-quantity');
        this.measurementUnitInput = <HTMLSelectElement>document.querySelector('.select-measurement-unit');
        this.addToCartButton = <HTMLButtonElement>document.getElementById('add-to-cart-button');

        this.initJson();
        this.initTranslations();
        this.initCurrentSalesUnit();
        this.mapEvents();
    }

    initJson() {
        let jsonSchemaContainer = document.getElementsByClassName(this.componentName + '__json')[0];
        if (jsonSchemaContainer.hasAttribute('json')) {
            let jsonString = jsonSchemaContainer.getAttribute('json');
            let jsonData = JSON.parse(jsonString);

            if (jsonData.hasOwnProperty('baseUnit')) {
                this.baseUnit = jsonData.baseUnit;
            }

            if (jsonData.hasOwnProperty('salesUnits')) {
                this.salesUnits = jsonData.salesUnits;
            }

            if (jsonData.hasOwnProperty('productQuantityStorage')) {
                this.productQuantityStorage = jsonData.productQuantityStorage;
            }
        }
    }

    initTranslations() {
        this.translations = JSON.parse(document.getElementById('measurement-unit-translation').innerHTML)
    }

    initCurrentSalesUnit() {
        for (let key in this.salesUnits) {
            if (this.salesUnits.hasOwnProperty(key)) {
                if (this.salesUnits[key].is_default) {
                    this.currentSalesUnit = this.salesUnits[key];
                }
            }
        }
    }

    mapEvents() {
        this.qtyInSalesUnitInput.addEventListener('change', (event: Event) => this.qtyInputChange());
        this.measurementUnitInput.addEventListener('change', (event: Event) => this.measurementUnitInputChange(event));
    }

    qtyInputChange(qtyInSalesUnits?: number) {
        if (typeof qtyInSalesUnits === 'undefined') {
            qtyInSalesUnits = +this.qtyInSalesUnitInput.value;
        }
        let error = false;
        let qtyInBaseUnits = this.multiply(qtyInSalesUnits, +this.currentSalesUnit.conversion);
        if ((qtyInBaseUnits - this.getMinQuantity()) % this.getQuantityInterval() !== 0) {
            error = true;
            this.hideNotifications();
            document.getElementById('quantity-between-units').classList.remove('is-hidden');
        } else if (qtyInBaseUnits < this.getMinQuantity()) {
            error = true;
            this.hideNotifications();
            document.getElementById('minimum-quantity').classList.remove('is-hidden');
        } else if (this.getMaxQuantity() > 0 && qtyInBaseUnits > this.getMaxQuantity()) {
            error = true;
            this.hideNotifications();
            document.getElementById('maximum-quantity').classList.remove('is-hidden');
        }

        if (error) {
            this.addToCartButton.setAttribute("disabled", "disabled");
            this.askCustomerForCorrectInput(qtyInSalesUnits);
            return;
        }
        this.qtyInBaseUnitInput.value = qtyInBaseUnits.toString();
        this.addToCartButton.removeAttribute("disabled");
        this.hideNotifications();
        return;
    }

    hideNotifications() {
        document.querySelector('.measurement-unit-choice').classList.add('is-hidden');
        document.getElementById('quantity-between-units').classList.add('is-hidden');
        document.getElementById('minimum-quantity').classList.add('is-hidden');
        document.getElementById('maximum-quantity').classList.add('is-hidden');
    }

    askCustomerForCorrectInput(qtyInSalesUnits: number) {
        let choicesList = document.querySelector('#measurement-unit-choices .list');
        let currentChoice = document.querySelector('.measurement-unit-choice #current-choice');
        let minChoice = this.getMinChoice(qtyInSalesUnits);
        let maxChoice = this.getMaxChoice(qtyInSalesUnits, minChoice);
        choicesList.innerHTML = '';
        currentChoice.innerHTML = '';
        currentChoice.textContent = `${this.round(qtyInSalesUnits, 4)} ${this.getUnitName(this.currentSalesUnit.product_measurement_unit.code)}`;

        let choiceElements = [];
        choiceElements.push(this.createChoiceElement(minChoice));
        if (maxChoice != minChoice) {
            choiceElements.push(this.createChoiceElement(maxChoice));
        }

        choiceElements.forEach((element) => (element !== null) ? choicesList.appendChild(element) : null);

        document.querySelector('.measurement-unit-choice').classList.remove('is-hidden');
    }

    createChoiceElement(qtyInBaseUnits: number) {
        if (qtyInBaseUnits > 0) {
            let choiceElem = document.createElement('span');
            let qtyInSalesUnits = qtyInBaseUnits / this.currentSalesUnit.conversion;
            let measurementSalesUnitName = this.getUnitName(this.currentSalesUnit.product_measurement_unit.code);
            let measurementBaseUnitName = this.getUnitName(this.baseUnit.code);

            choiceElem.classList.add('link');
            choiceElem.setAttribute('data-base-unit-qty', qtyInBaseUnits.toString());
            choiceElem.setAttribute('data-sales-unit-qty', qtyInSalesUnits.toString());
            choiceElem.textContent = `(${this.round(qtyInSalesUnits, 4).toString().toString()} ${measurementSalesUnitName}) = (${qtyInBaseUnits} ${measurementBaseUnitName})`;
            choiceElem.onclick = function (event: Event) {
                let element = event.srcElement as HTMLSelectElement;
                let qtyInBaseUnits = parseFloat(element.dataset.baseUnitQty);
                let qtyInSalesUnits = parseFloat(element.dataset.salesUnitQty);
                this.selectQty(qtyInBaseUnits, qtyInSalesUnits);
            }.bind(this);

            choiceElem.style.display = 'block';

            return choiceElem;
        }

        return null;
    }

    selectQty(qtyInBaseUnits: number, qtyInSalesUnits: number) {
        this.qtyInBaseUnitInput.value = qtyInBaseUnits.toString();
        this.qtyInSalesUnitInput.value = this.round(qtyInSalesUnits, 4).toString().toString();
        this.addToCartButton.removeAttribute("disabled");
        document.querySelector('.measurement-unit-choice').classList.add('is-hidden');
    }

    getMinChoice(qtyInSalesUnits: number) {
        let qtyInBaseUnits = this.floor(this.multiply(qtyInSalesUnits, this.currentSalesUnit.conversion));

        if (qtyInBaseUnits < this.getMinQuantity()) {
            return this.getMinQuantity();
        }

        if ((qtyInBaseUnits - this.getMinQuantity()) % this.getQuantityInterval() !== 0 || (this.getMaxQuantity() > 0 && qtyInBaseUnits > this.getMaxQuantity())) {
            return this.getMinChoice((qtyInBaseUnits - 1) / this.currentSalesUnit.conversion)
        }

        return qtyInBaseUnits;
    }

    getMaxChoice(qtyInSalesUnits: number, minChoice: number) {
        let qtyInBaseUnits = this.ceil(this.multiply(qtyInSalesUnits, this.currentSalesUnit.conversion));

        if (this.getMaxQuantity() > 0 && qtyInBaseUnits > this.getMaxQuantity()) {
            qtyInBaseUnits = this.getMaxQuantity();

            if ((qtyInBaseUnits - this.getMinQuantity()) % this.getQuantityInterval() !== 0) {
                qtyInBaseUnits = qtyInBaseUnits - ((qtyInBaseUnits - this.getMinQuantity()) % this.getQuantityInterval());
            }

            return qtyInBaseUnits;
        }

        if ((qtyInBaseUnits - this.getMinQuantity()) % this.getQuantityInterval() !== 0 || qtyInBaseUnits <= minChoice) {
            return this.getMaxChoice((qtyInBaseUnits + 1) / this.currentSalesUnit.conversion, minChoice)
        }

        return qtyInBaseUnits;
    }

    floor(value: number): number {
        if (Math.floor(value) > 0) {
            return Math.floor(value);
        }

        return Math.ceil(value);
    }

    ceil(value: number): number {
        return Math.ceil(value);
    }

    round(value: number, decimals: number): number {
        return Number(Math.round(parseFloat(value + 'e' + decimals)) + 'e-' + decimals);
    }

    multiply(a: number, b: number): number {
        let result = ((a * 10) * (b * 10)) / 100;
        return Math.floor(result * 1000) / 1000;
    }

    getMinQuantity() {
        if (this.productQuantityStorage.hasOwnProperty('quantity_min')) {
            return this.productQuantityStorage.quantity_min;
        }

        return 1;
    }

    getMaxQuantity() {
        if (this.productQuantityStorage.hasOwnProperty('quantity_max') && this.productQuantityStorage.quantity_max !== null) {
            return this.productQuantityStorage.quantity_max;
        }

        return 0;
    }

    getQuantityInterval() {
        if (this.productQuantityStorage.hasOwnProperty('quantity_interval')) {
            return this.productQuantityStorage.quantity_interval;
        }

        return 1;
    }

    measurementUnitInputChange(event: Event) {
        let salesUnitId = parseInt((event.srcElement as HTMLSelectElement).value);
        let salesUnit = this.getSalesUnitById(salesUnitId);
        let qtyInSalesUnits = +this.qtyInSalesUnitInput.value;
        let qtyInBaseUnits = this.multiply(qtyInSalesUnits, this.currentSalesUnit.conversion);
        qtyInSalesUnits = qtyInBaseUnits / salesUnit.conversion;
        this.currentSalesUnit = salesUnit;
        this.qtyInSalesUnitInput.value = this.round(qtyInSalesUnits, 4).toString();
        this.qtyInputChange(qtyInSalesUnits);
    }

    getSalesUnitById(salesUnitId: number) {
        for (let key in this.salesUnits) {
            if (this.salesUnits.hasOwnProperty(key)) {
                if (salesUnitId == this.salesUnits[key].id_product_measurement_sales_unit) {
                    return this.salesUnits[key];
                }
            }
        }
    }

    getBaseSalesUnit() {
        for (let key in this.salesUnits) {
            if (this.salesUnits.hasOwnProperty(key)) {
                if (this.baseUnit.id_product_measurement_unit == this.salesUnits[key].product_measurement_unit.id_product_measurement_unit) {
                    return this.salesUnits[key];
                }
            }
        }
    }

    getUnitName(key) {
        if (this.translations.hasOwnProperty(key)) {
            return this.translations[key];
        }

        return key;
    }
}
