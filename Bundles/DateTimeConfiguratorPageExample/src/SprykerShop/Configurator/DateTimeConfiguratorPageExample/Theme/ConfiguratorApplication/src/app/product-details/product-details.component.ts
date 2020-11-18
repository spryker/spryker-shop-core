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
    dateInputDelayId: number;
    dateInputDelay = 1000;
    form: HTMLFormElement;
    isFormSubmitted = false;
    today = new Date();
    dayTimeOptions = [
        'deliveryOptions.select',
        'deliveryOptions.morning',
        'deliveryOptions.lunch',
        'deliveryOptions.afternoon',
        'deliveryOptions.evening'
    ];
    dayTimeOptionsJson = [
        'Morning',
        'Lunch hour',
        'Afternoon',
        'Evening'
    ];

    constructor(
        private productService: ProductService,
        private configuratorService: ConfiguratorService,
    ) {}

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

        this.configuratorService.updateDayTime(
            optionIndex,
            this.dayTimeOptionsJson[optionIndex],
            this.productData
        );
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
