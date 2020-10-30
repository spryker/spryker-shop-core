import { Component, ChangeDetectionStrategy, OnInit, Input } from "@angular/core";
import { ProductData } from '../../services/types';
import { ProductService } from '../../services/product.service';

@Component({
    selector: 'app-json-output',
    templateUrl: './json-output.component.html',
    styleUrls: ['./json-output.component.scss'],
    changeDetection: ChangeDetectionStrategy.OnPush
})
export class JsonOutputComponent implements OnInit {
    configurationOriginJSON: ProductData;
    @Input() productData: ProductData;

    constructor(
        private productService: ProductService
    ) {}

    ngOnInit(): void {
        this.configurationOriginJSON = this.productService.productData;
    }
}
