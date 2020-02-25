import Component from 'ShopUi/models/component';

export default class QuoteRequestHistorySelect extends Component {
    protected select: HTMLSelectElement;

    protected readyCallback(): void {}

    protected init(): void {
        this.select = <HTMLSelectElement>this.getElementsByTagName('select')[0];
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.mapSelectChangeEvent();
    }

    protected mapSelectChangeEvent(): void {
        this.select.addEventListener('change', () => this.onChange());
    }

    protected onChange(): void {
        const selectedValue = this.select.options[this.select.selectedIndex].value;
        if (selectedValue) {
            window.location.search = `${this.versionReferenceTitle}=${selectedValue}`;
        }
    }

    protected get versionReferenceTitle(): string {
        return this.getAttribute('version-reference-title');
    }
}
