import { Pipe, PipeTransform } from '@angular/core';
import { ConfiguredProduct, VolumePrices } from '../../services/types';

@Pipe({
    name: 'configurePrice'
})
export class ConfigurePricePipe implements PipeTransform {
    transform(productData: ConfiguredProduct, isTotal = false): number {
        if (productData.quantity < 5 || !productData.volume_prices) {
            return productData.price / 100;
        }

        const isNetMode = productData.price_mode.toLowerCase().trim() === 'net_mode';
        const volumePrice = (productData.volume_prices as VolumePrices).volume_prices[0];
        const configuredPrice = isNetMode ? volumePrice.net_price : volumePrice.gross_price;

        return (configuredPrice / 100) * (isTotal ? productData.quantity : 1);
    }
}
