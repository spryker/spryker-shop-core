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
        let qtyInBaseUnits = qtyInSalesUnits * +this.currentSalesUnit.conversion;
        if (qtyInBaseUnits % 1 != 0) {
            this.addToCartButton.setAttribute("disabled", "disabled");
            this.askCustomerForCorrectInput(qtyInSalesUnits);
            return;
        }
        this.qtyInBaseUnitInput.value = qtyInBaseUnits.toString();
        this.addToCartButton.removeAttribute("disabled");
        document.querySelector('.measurement-unit-choice').classList.add('is-hidden');
        return;
    }

    askCustomerForCorrectInput(qtyInSalesUnits: number) {
        let choicesList = document.querySelector('#measurement-unit-choices .list');
        let currentChoice = document.querySelector('.measurement-unit-choice #current-choice');
        let minChoice = this.getMinChoice(qtyInSalesUnits);
        let maxChoice = this.getMaxChoice(qtyInSalesUnits);
        choicesList.innerHTML = '';
        currentChoice.innerHTML = '';
        currentChoice.textContent = `${qtyInSalesUnits} ${this.currentSalesUnit.product_measurement_unit.code}`;

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
            choiceElem.textContent = `(${this.round(qtyInSalesUnits, 3).toString().toString()} ${measurementSalesUnitName}) = (${qtyInBaseUnits} ${measurementBaseUnitName})`;
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
        this.qtyInSalesUnitInput.value = this.round(qtyInSalesUnits, 3).toString().toString();
        this.addToCartButton.removeAttribute("disabled");
        document.querySelector('.measurement-unit-choice').classList.add('is-hidden');
    }

    getMinChoice(qtyInSalesUnits: number) {
        let qtyInBaseUnits = this.floor(qtyInSalesUnits * this.currentSalesUnit.conversion);
        qtyInBaseUnits = this.floor(qtyInBaseUnits - (qtyInBaseUnits % this.getQuantityInterval()));

        if (qtyInBaseUnits < this.getMinQuantity()) {
            qtyInBaseUnits = this.getMinQuantity();
        }

        return qtyInBaseUnits;
    }

    getMaxChoice(qtyInSalesUnits: number) {
        let qtyInBaseUnits = this.ceil(qtyInSalesUnits * this.currentSalesUnit.conversion);
        qtyInBaseUnits = this.ceil(qtyInBaseUnits + (qtyInBaseUnits % this.getQuantityInterval()));

        if (this.getMaxQuantity() > 0 && qtyInBaseUnits > this.getMaxQuantity()) {
            qtyInBaseUnits = this.getMaxQuantity();
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

    getMinQuantity() {
        if (this.productQuantityStorage.hasOwnProperty('quantity_min')) {
            return this.productQuantityStorage.quantity_min;
        }

        return 1;
    }

    getMaxQuantity() {
        if (this.productQuantityStorage.hasOwnProperty('quantity_max')) {
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
        let qtyInBaseUnits = qtyInSalesUnits * this.currentSalesUnit.conversion;
        qtyInSalesUnits = qtyInBaseUnits / salesUnit.conversion;
        this.currentSalesUnit = salesUnit;
        this.qtyInSalesUnitInput.value = this.round(qtyInSalesUnits, 3).toString();
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
