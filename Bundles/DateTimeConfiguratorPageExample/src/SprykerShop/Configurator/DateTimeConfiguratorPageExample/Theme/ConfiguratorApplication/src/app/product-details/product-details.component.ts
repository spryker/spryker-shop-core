import { ChangeDetectionStrategy, Component, Input, OnChanges, SimpleChanges } from '@angular/core';
import { ConfiguredProduct } from '../../services/types';
import { ConfiguratorService } from '../../services/configurator.service';
import { ProductService } from '../../services/product.service';

@Component({
    selector: 'app-product-details',
    templateUrl: './product-details.component.html',
    styleUrls: ['./product-details.component.scss'],
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class ProductDetailsComponent implements OnChanges {
    @Input() productData: ConfiguredProduct;
    dateInputDelayId: ReturnType<typeof setTimeout>;
    dateInputDelay = 1000;
    form: HTMLFormElement;
    isFormSubmitted = false;
    today = new Date();
    dayTimeOptions = [
        'deliveryOptions.select',
        'deliveryOptions.morning',
        'deliveryOptions.lunch',
        'deliveryOptions.afternoon',
        'deliveryOptions.evening',
    ];
    dayTimeOptionsJson = ['Morning', 'Lunch hour', 'Afternoon', 'Evening'];

    constructor(private productService: ProductService, private configuratorService: ConfiguratorService) {}

    ngOnChanges(changes: SimpleChanges): void {
        if (!this.productData.timestamp && !this.productData.checkSum) {
            return;
        }

        this.handleSubmit();
    }

    isComplete(): boolean {
        const isDayTimeSelected = this.productData.configuration.time_of_day !== null;
        const isDateEntered = this.productData.display_data.Date.length > 0;

        return isDayTimeSelected && isDateEntered;
    }

    isAvailableQuantityChange(): boolean {
        const isAavailableQuantityNumber = typeof this.productData.available_quantity === 'number';
        const isQuantityBiggerThanAvailability = this.productData.available_quantity < this.productData.quantity;

        return isAavailableQuantityNumber && isQuantityBiggerThanAvailability;
    }

    isProductAvailable(): boolean {
        const atStoreName = 'at';
        const dayTimeMorningName = 'morning';
        const displayDataFieldName = 'Preferred time of the day';

        const isAtStore = this.productData.store_name.toLowerCase().trim() === atStoreName;
        const dayTime = this.productData.display_data[displayDataFieldName];
        const isDayTimeMorning = dayTime.toLowerCase().trim() === dayTimeMorningName;

        return !(isAtStore && isDayTimeMorning);
    }

    isOptionActive(index: number): boolean {
        if (this.productData.configuration.time_of_day === null) {
            return false;
        }

        return Number(this.productData.configuration.time_of_day) === index;
    }

    isSubmitDisabled(): boolean {
        const sourceTypeCart = 'source_type_cart';

        const isProductAvailable = this.isProductAvailable();
        const isProductInCart = this.productData.source_type.toLowerCase().trim() === sourceTypeCart;

        return !isProductAvailable && isProductInCart;
    }

    onDateChange(event: Event): void {
        clearTimeout(this.dateInputDelayId);

        this.dateInputDelayId = setTimeout(() => {
            const dateInput = event.target as HTMLInputElement;

            this.configuratorService.updateDate(dateInput.value, this.productData);
        }, this.dateInputDelay);
    }

    onDayTimeChange(event: Event): void {
        const select = event.target as HTMLInputElement;
        const selectedOption = select.value;

        if (!selectedOption) {
            this.configuratorService.updateDayTime(null, '', this.productData);

            return;
        }

        const optionIndex = Number(selectedOption);

        this.configuratorService.updateDayTime(optionIndex, this.dayTimeOptionsJson[optionIndex], this.productData);
    }

    onSubmit(event: Event): void | boolean {
        if (this.isFormSubmitted) {
            return;
        }

        event.preventDefault();
        this.form = event.target as HTMLFormElement;
        const formData = new FormData(this.form);

        this.productService.sendData(formData).subscribe((response: ConfiguredProduct) => {
            this.configuratorService.updateWithGeneratedProductData({
                ...this.productData,
                timestamp: response.timestamp,
                checkSum: response.checkSum,
            });
        });

        return false;
    }

    handleSubmit(): void {
        this.configuratorService.configurator.complete();
        this.isFormSubmitted = true;

        setTimeout(() => {
            this.form.submit();
        }, 0);
    }
}
