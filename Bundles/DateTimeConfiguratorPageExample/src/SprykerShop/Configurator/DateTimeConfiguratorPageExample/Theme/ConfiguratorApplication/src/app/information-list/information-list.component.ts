import { Component, ChangeDetectionStrategy, OnChanges, Input } from '@angular/core';
import { ProductData } from '../../services/types';

export interface InformationItem {
    title: string;
    value: string;
}

@Component({
    selector: 'app-information-list',
    templateUrl: './information-list.component.html',
    styleUrls: ['./information-list.component.scss'],
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class InformationListComponent implements OnChanges {
    @Input() productData: ProductData;
    items: InformationItem[] = [];

    constructor() {}

    ngOnChanges(): void {
        this.items = [
            {
                title: 'global.store',
                value: this.productData.store_name,
            },
            {
                title: 'global.locale',
                value: this.productData.locale_name,
            },
            {
                title: 'global.priceMode',
                value: this.productData.price_mode,
            },
            {
                title: 'global.currency',
                value: this.productData.currency_code,
            },
            {
                title: 'global.customerId',
                value: this.productData.customer_reference,
            },
        ];
    }
}
