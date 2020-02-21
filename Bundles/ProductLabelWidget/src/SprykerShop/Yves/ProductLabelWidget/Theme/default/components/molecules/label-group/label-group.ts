import Component from 'ShopUi/models/component';
import ProductItem, { EVENT_UPDATE_LABELS, ProductItemLabelsData } from 'ShopUi/components/molecules/product-item/product-item';

export default class LabelGroup extends Component {
    protected productLabelFlags: HTMLElement[];
    protected productLabelTag: HTMLElement;
    protected productItem: ProductItem;

    protected readyCallback(): void {}

    protected init(): void {
        this.productLabelFlags = <HTMLElement[]>Array.from(this.getElementsByClassName(`${this.jsName}__label-flag`));
        this.productLabelTag = <HTMLElement>this.getElementsByClassName(`${this.jsName}__label-tag`)[0];
        if (this.productItemClassName) {
            this.productItem = <ProductItem>this.closest(`.${this.productItemClassName}`);
        }

        this.mapEvents();
    }

    protected mapEvents(): void {
        if (!this.productItem) {
            return;
        }

        this.mapProductItemUpdateLabelsCustomEvent();
    }

    protected mapProductItemUpdateLabelsCustomEvent() {
        this.productItem.addEventListener(EVENT_UPDATE_LABELS, (event: Event) => {
            this.setProductLabels((<CustomEvent>event).detail.labels);
        });
    }

    /**
     * Sets the product labels.
     * @param labels An array of product labels.
     */
    setProductLabels(labels: ProductItemLabelsData[]) {
        if (!labels.length) {
            this.productLabelFlags.forEach((element: HTMLElement) => element.classList.add(this.classToToggle));
            this.productLabelTag.classList.add(this.classToToggle);

            return;
        }

        const labelTagType = this.productLabelTag.getAttribute('data-label-tag-type');
        const labelFlags = labels.filter((element: ProductItemLabelsData) => element.type !== labelTagType);
        const labelTag = labels.filter((element: ProductItemLabelsData) => element.type === labelTagType);

        if (!labelTag.length) {
            this.productLabelTag.classList.add(this.classToToggle);
        }

        if (!labelFlags.length) {
            this.productLabelFlags.forEach((element: HTMLElement) => element.classList.add(this.classToToggle));
        }

        this.updateProductLabels(labelFlags, labelTag);
    }

    protected updateProductLabelTag(element: ProductItemLabelsData): void {
        const labelTagTextContent = <HTMLElement>this.productLabelTag.getElementsByClassName(`${this.jsName}__label-tag-text`)[0];

        this.productLabelTag.classList.remove(this.classToToggle);
        labelTagTextContent.innerText = element.text;
    }

    protected createProductLabelFlagClones(index: number): void {
        if (index < 1) {
            return;
        }

        const cloneLabelFlag = this.productLabelFlags[0].cloneNode(true);
        this.productLabelFlags[0].parentNode.insertBefore(cloneLabelFlag, this.productLabelFlags[0].nextSibling);
        this.productLabelFlags = <HTMLElement[]>Array.from(this.getElementsByClassName(`${this.jsName}__label-flag`));
    }

    protected deleteProductLabelFlagClones(labelFlags: ProductItemLabelsData[]): void {
        while (this.productLabelFlags.length > labelFlags.length) {
            this.productLabelFlags[this.productLabelFlags.length - 1].remove();
            this.productLabelFlags = <HTMLElement[]>Array.from(
                this.getElementsByClassName(`${this.jsName}__label-flag`)
            );
        }
    }

    protected deleteProductLabelFlagModifiers(index: number): void {
        this.productLabelFlags[index].classList.forEach((element: string) => {
            if (element.includes('--')) {
                this.productLabelFlags[index].classList.remove(element);
            }
        });
    }

    protected updateProductLabelFlags(element: ProductItemLabelsData, index: number): void {
        const labelFlagClassName: string = this.productLabelFlags[index].getAttribute('data-config-name');
        const labelFlagTextContent = <HTMLElement>this.productLabelFlags[index].getElementsByClassName(`${this.jsName}__label-flag-text`)[0];

        if (element.type) {
            this.productLabelFlags[index].classList.add(`${labelFlagClassName}--${element.type}`);
        }

        this.productLabelFlags[index].classList.remove(this.classToToggle);
        labelFlagTextContent.innerText = element.text;
    }

    protected updateProductLabels(labelFlags: ProductItemLabelsData[], labelTag: ProductItemLabelsData[]): void {
        if (labelTag.length) {
            this.updateProductLabelTag(labelTag[0]);
        }

        if (labelFlags.length) {
            labelFlags.forEach((element: ProductItemLabelsData, index: number) => {
                this.createProductLabelFlagClones(index);
                this.deleteProductLabelFlagClones(labelFlags);
                this.deleteProductLabelFlagModifiers(index);
                this.updateProductLabelFlags(element, index);
            });
        }
    }

    protected get classToToggle(): string {
        return this.getAttribute('class-to-toggle');
    }

    protected get productItemClassName(): string {
        return this.getAttribute('product-item-class-name');
    }
}
