import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';

import { AppComponent } from './app.component';
import { HeaderComponent } from './header/header.component';
import { InformationListComponent } from './information-list/information-list.component';

import { HttpClient, HttpClientModule } from '@angular/common/http';
import { ProductService } from '../services/product.service';
import { TranslateModule, TranslateLoader } from '@ngx-translate/core';
import { TranslateHttpLoader } from '@ngx-translate/http-loader';
import { FooterComponent } from './footer/footer.component';
import { ProductDetailsComponent } from './product-details/product-details.component';
import { JsonOutputComponent } from './json-output/json-output.component';
import { ConfiguratorService } from '../services/configurator.service';

export function HttpLoaderFactory(httpClient: HttpClient): TranslateLoader {
    return new TranslateHttpLoader(httpClient, './dist/assets/i18n/');
}

@NgModule({
    declarations: [
        AppComponent,
        HeaderComponent,
        InformationListComponent,
        FooterComponent,
        ProductDetailsComponent,
        JsonOutputComponent,
    ],
    imports: [
        BrowserModule,
        HttpClientModule,
        TranslateModule.forRoot({
            loader: {
                provide: TranslateLoader,
                useFactory: HttpLoaderFactory,
                deps: [HttpClient],
            },
        }),
    ],
    providers: [ProductService, ConfiguratorService],
    bootstrap: [AppComponent],
})
export class AppModule {}
