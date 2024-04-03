import { ChangeDetectionStrategy, Component, ViewChild } from '@angular/core';
import { combineLatest, map } from 'rxjs';
import { ConfiguratorService } from 'src/services/configurator.service';
import { ProductConfiguratorComponent } from '../product-configurator/product-configurator.component';

@Component({
    selector: 'app-product-details',
    templateUrl: './product-details.component.html',
    styleUrls: ['./product-details.component.scss'],
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class ProductDetailsComponent {
    constructor(private configuratorService: ConfiguratorService) {}

    @ViewChild('configurator') configurator: ProductConfiguratorComponent;

    productData$ = this.configuratorService.configurator$;
    productInfo$ = this.configuratorService.productData$;

    data$ = combineLatest([this.productData$, this.productInfo$]).pipe(
        map(([configurator, productData]) => ({ configurator, product: productData })),
    );

    onSubmit(): void {
        this.configurator.sendMetaData$.next();
    }
}
