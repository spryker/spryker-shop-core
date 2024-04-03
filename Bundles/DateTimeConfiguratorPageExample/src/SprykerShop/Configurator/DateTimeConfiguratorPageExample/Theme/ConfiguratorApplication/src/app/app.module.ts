import { CommonModule } from '@angular/common';
import { HttpClient, HttpClientModule } from '@angular/common/http';
import { NgModule } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { BrowserModule } from '@angular/platform-browser';
import { TranslateLoader, TranslateModule } from '@ngx-translate/core';
import { TranslateHttpLoader } from '@ngx-translate/http-loader';
import { ASSETS } from '../services/configurator.service';
import { ConfigurePricePipe } from '../utils/configure-price.pipe';
import { AppComponent } from './app.component';
import { ConfiguratorGroupComponent } from './configurator-group/configurator-group.component';
import { ConfiguratorItemComponent } from './configurator-item/configurator-item.component';
import { FooterComponent } from './footer/footer.component';
import { HeaderComponent } from './header/header.component';
import { ProductConfiguratorComponent } from './product-configurator/product-configurator.component';
import { ProductDetailsComponent } from './product-details/product-details.component';

export function HttpLoaderFactory(httpClient: HttpClient): TranslateLoader {
    return new TranslateHttpLoader(httpClient, `${ASSETS}/assets/i18n/`);
}

@NgModule({
    declarations: [
        AppComponent,
        HeaderComponent,
        FooterComponent,
        ProductDetailsComponent,
        ConfigurePricePipe,
        ProductConfiguratorComponent,
        ConfiguratorGroupComponent,
        ConfiguratorItemComponent,
    ],
    imports: [
        FormsModule,
        CommonModule,
        BrowserModule,
        HttpClientModule,
        TranslateModule,
        TranslateModule.forRoot({
            loader: {
                provide: TranslateLoader,
                useFactory: HttpLoaderFactory,
                deps: [HttpClient],
            },
        }),
    ],
    bootstrap: [AppComponent],
})
export class AppModule {}
