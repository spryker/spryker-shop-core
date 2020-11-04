import { Pipe, PipeTransform } from '@angular/core';
import { VolumePrices } from '../../services/types';

interface ProductPrice {
    price_mode: string;
    volume_prices: VolumePrices;
    quantity?: number;
}

@Pipe({
    name: 'configurePrice'
})
export class ConfigurePricePipe implements PipeTransform {
    transform(price: number, priceData: ProductPrice): number {
        if (priceData.quantity < 5 || !priceData.volume_prices) {
            return price / 100;
        }

        const isNetMode = priceData.price_mode.toLowerCase().trim() === 'net_mode';
        const volumePrice = priceData.volume_prices.volume_prices[0];
        const configuredPrice = isNetMode ? volumePrice.net_price : volumePrice.gross_price;

        return (configuredPrice / 100) * (priceData.quantity ? priceData.quantity : 1);
    }
}
