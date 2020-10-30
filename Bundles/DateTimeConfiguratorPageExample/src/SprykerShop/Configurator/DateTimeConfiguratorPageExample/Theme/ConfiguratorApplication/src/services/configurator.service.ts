import { Injectable } from '@angular/core';
import { ReplaySubject } from 'rxjs';
import { ConfiguredProduct, DateConfiguration, DayConfiguration, ServerData, VolumePrices } from './types';

@Injectable()
export class ConfiguratorService {
    configurator = new ReplaySubject();
    defaultPrice = 30000;
    volumePricesGross = {
        volume_prices: [{
            quantity: 5,
            net_price: 28500,
            gross_price: 29000
        }]
    };
    volumePricesNet = {
        volume_prices: [{
            quantity: 5,
            net_price: 23500,
            gross_price: 24000
        }]
    };

    constructor() {}

    generateConfiguredData(response: ServerData): void {
        const configuration = JSON.parse(response.configuration) as DayConfiguration;
        const displayData = this.generateDate(JSON.parse(response.display_data));
        const productData = Object.assign({}, response, {
            configuration: configuration,
            display_data: displayData
        } as ConfiguredProduct);

        this.configurator.next({
            ...productData,
            ...this.updateConfiguredValues(productData)
        })
    }

    generateDate(displayData: DateConfiguration): DateConfiguration {
        const today = new Date().getTime();
        const serverDate = new Date(displayData.Date).getTime();

        if (serverDate < today) {
            displayData.Date = '';
        }

        return displayData;
    }

    updateDate(date: string, productData: ConfiguredProduct): void {
        const displayTime = productData.display_data;

        displayTime.Date = this.setDate(date);

        productData.display_data = displayTime;
        productData = this.updateConfiguredValues(productData);

        this.updateWithGeneratedProductData(productData);
    }

    updateDayTime(index: number, dayTime: string, productData: ConfiguredProduct): void {
        const displayTime = productData.display_data;
        const configuration = productData.configuration;

        displayTime['Preferred time of the day'] = dayTime;
        configuration.time_of_day = index;

        productData.display_data = displayTime;
        productData.configuration = configuration;
        productData = this.updateConfiguredValues(productData);

        this.updateWithGeneratedProductData(productData);
    }

    updateConfiguredValues(productData: ConfiguredProduct): ConfiguredProduct {
        const isAtStore = productData.store_name.toLowerCase().trim() == 'at';
        const date = productData.display_data.Date;
        const dayTime = productData.display_data['Preferred time of the day'];

        productData.price = this.setPrice(
            productData.currency_code,
            productData.display_data,
            productData.price_mode,
            isAtStore
        );
        productData.volume_prices = this.setVolumePrices(
            productData.currency_code,
            date,
            dayTime,
            productData.price_mode,
            isAtStore
        );
        productData.available_quantity = this.setQuantity(isAtStore, date, dayTime);

        return productData;
    }

    setPrice(currency: string, displayData: DateConfiguration, priceMode: string, isAtStore: boolean): number | null {
        const isDayTimeEvening = displayData["Preferred time of the day"].toLowerCase().trim() === 'evening';

        if (!isAtStore || !displayData.Date || !isDayTimeEvening) {
            return null;
        }

        const isNetMode = priceMode.toLowerCase().trim() === 'net_mode';

        return this.defaultPrice - (isNetMode ? 5000 : 0);
    }

    setVolumePrices(currency: string, date: string, dayTime: string, priceMode: string, isAtStore: boolean): VolumePrices | {} {
        const isEvening = dayTime.toLowerCase().trim() === 'evening';

        if (!isAtStore || !date || !isEvening) {
            return {};
        }

        const isNetMode = priceMode.toLowerCase().trim() === 'net_mode';

        return isNetMode ? this.volumePricesNet : this.volumePricesGross;
    }

    setQuantity(isAtStore: boolean, date: string, dayTime: string): number | string {
        const isDaytimeLunch = dayTime.toLowerCase().trim() === 'lunch hour';

        if (!isAtStore || !date || !isDaytimeLunch) {
            return '';
        }

        return 5;
    }

    updateWithGeneratedProductData(newProductData): void {
        this.configurator.next(Object.assign({}, newProductData));
    }

    setDate(date: string): string {
        return date.split('-').reverse().join('.');
    }

    parseDate(date: string): string {
        return date.split('.').reverse().join('-');
    }

    remove(propertyName: string, productData: ConfiguredProduct): void {
        delete productData[propertyName];

        this.updateWithGeneratedProductData(productData);
    }
}
