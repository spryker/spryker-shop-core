import Component from '../../../models/component';

export default class MeasurementQuantitySelector extends Component {
    triggers: HTMLElement[];
    baseUnit: any;
    salesUnits: any;
    currentSalesUnit: any;
    currentValue: Number;
    addToCartButton: HTMLElement;

    readyCallback(event?: Event): void {
        this.triggers = <HTMLElement[]>Array.from(document.querySelectorAll(MeasurementQuantitySelector.triggerSelectors));
        this.addToCartButton = document.getElementById('add-to-cart-button');

        this.initJson();
        this.mapEvents();
    }

    initJson() {
        let jsonSchemaContainer = document.getElementsByClassName(this.name + '__json')[0];
        if (jsonSchemaContainer.hasAttribute('json')) {
            let jsonString = jsonSchemaContainer.getAttribute('json');
            let jsonData = JSON.parse(jsonString);

            if (jsonData.hasOwnProperty('baseUnit')) {
                this.baseUnit = jsonData.baseUnit;
            }

            if (jsonData.hasOwnProperty('salesUnits')) {
                this.salesUnits = jsonData.salesUnits;
            }
        }
    }

    mapEvents() {
        this.triggers.forEach((trigger: HTMLElement) => trigger.addEventListener('change', (event: Event) => this.onTriggerSelect(event)));
    }

    onTriggerSelect(event: Event) {
        event.preventDefault();
        let baseUnitValue = this.calculateNormalizedQuantity();

        if (baseUnitValue % 1 != 0) {
            this.addToCartButton.setAttribute("disabled", "disabled");
            this.showChoice(baseUnitValue);
        } else {
            this.addToCartButton.removeAttribute("disabled");
            MeasurementQuantitySelector.hideChoice();
        }
    }

    showChoice(baseUnitValue) {
        let choices = [
            Math.ceil(baseUnitValue),
            Math.floor(baseUnitValue)
        ].filter(Number)
            .sort();

        let choicesList = document.querySelector('#measurement-unit-choices .list');
        let currentChoice = document.querySelector('.measurement-unit-choice #current-choice');
        choicesList.innerHTML = '';
        currentChoice.innerHTML = '';
        currentChoice.textContent = `${this.currentValue} ${this.currentSalesUnit.product_measurement_unit.code}`;

        let currentBaseUnit = {};
        for (let key in this.salesUnits) {
            if (this.salesUnits.hasOwnProperty(key)) {
                if (this.baseUnit.id_product_measurement_unit == this.salesUnits[key].product_measurement_unit.id_product_measurement_unit) {
                    currentBaseUnit = this.salesUnits[key];
                }
            }
        }

        choices.forEach(function (choiceValue) {
            let choiceElem = document.createElement('span');
            let valueInSalesUnits = choiceValue / this.currentSalesUnit.conversion;
            let measurementSalesUnitCode = this.currentSalesUnit.product_measurement_unit.code;
            let valueInBaseUnits = valueInSalesUnits * this.currentSalesUnit.conversion;
            let measurementBaseUnitCode = this.baseUnit.code;
            choiceElem.classList.add('link');
            choiceElem.setAttribute('data-qty', valueInSalesUnits.toString());
            choiceElem.textContent = `(${valueInSalesUnits} ${measurementSalesUnitCode}) = (${valueInBaseUnits} ${measurementBaseUnitCode})`;
            choiceElem.onclick = function (event: Event) {
                event.preventDefault();
                MeasurementQuantitySelector.selectQty(parseInt(this.dataset.qty));
            };

            choiceElem.style.display = 'block';
            choicesList.appendChild(choiceElem);
        }, this);

        document.querySelector('.measurement-unit-choice').classList.remove('is-hidden');
    }

    static selectQty(qty) {
        let values = [];

        let qtySelect = document.querySelector('.select-quantity') as HTMLSelectElement;
        for (let i = 0; i < qtySelect.options.length; i++) {
            values.push(qtySelect.options[i].value);
        }
        if (values.includes(qty.toString())) {
            qtySelect.value = qty.toString();
            MeasurementQuantitySelector.hideChoice();
            if ("createEvent" in document) {
                let evt = document.createEvent("HTMLEvents");
                evt.initEvent("change", false, true);
                qtySelect.dispatchEvent(evt);
            } else {
                qtySelect.fireEvent("onchange");
            }
        }
    }

    static hideChoice() {
        document.querySelector('.measurement-unit-choice').classList.add('is-hidden');
    }

    calculateNormalizedQuantity(): number {
        let qtyInput = (document.querySelector('.select-quantity') as HTMLInputElement).value;
        let measurementSalesUnitIdInput = (document.querySelector('.select-measurement-unit') as HTMLInputElement).value;
        let baseUnitValue = parseInt(qtyInput);

        for (let key in this.salesUnits) {
            if (this.salesUnits.hasOwnProperty(key)) {

                if (measurementSalesUnitIdInput == this.salesUnits[key].id_product_measurement_sales_unit) {
                    this.currentSalesUnit = this.salesUnits[key];
                    this.currentValue = baseUnitValue;
                    return baseUnitValue * parseFloat(this.salesUnits[key].conversion);
                }
            }
        }

        return baseUnitValue;

    }

    static get triggerSelectors(): string {
        return '.select-quantity, .select-measurement-unit';
    }
}
