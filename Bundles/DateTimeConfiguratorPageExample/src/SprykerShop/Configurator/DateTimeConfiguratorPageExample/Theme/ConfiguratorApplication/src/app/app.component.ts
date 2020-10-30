import { ChangeDetectionStrategy, Component, OnInit } from "@angular/core";
import { TranslateService } from '@ngx-translate/core';
import { ProductService } from '../services/product.service';
import { ConfiguratorService } from '../services/configurator.service';
import { ProductData, ServerData } from '../services/types';

@Component({
    selector: 'app-root',
    templateUrl: './app.component.html',
    styleUrls: ['./app.component.scss'],
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class AppComponent implements OnInit {
    productData: ProductData;
    isDataLoaded = false;
    configuredProductJSON$ = this.configuratorService.configurator;

    constructor(
        public translate: TranslateService,
        private productService: ProductService,
        private configuratorService: ConfiguratorService
    ) {
        translate.addLangs(['en_US', 'de_DE']);
        this.translate.setDefaultLang('en_US');
    }

    ngOnInit(): void {
        this.productService.getData().subscribe((response: ServerData) => {
            if (!response) {
                return;
            }

            this.configuratorService.generateConfiguredData(response);

            this.setLanguage(response.locale_name);
        });
    }

    private setLanguage(locale: string): void {
        this.translate.use(locale).subscribe(() => this.updateLoader());
    }

    private updateLoader(): void {
        this.isDataLoaded = true;
    }
}
