export interface ProductData {
    sku: string;
    item_group_key: string;
    quantity: number;
    configurator_key: string;
    customer_reference: string;
    store_name: string;
    currency_code: string;
    locale_name: string;
    price_mode: string;
    source_type: string;
    back_url: string;
    submit_url: string;
    timestamp?: string;
    checkSum?: string;
}

export interface ServerData extends ProductData {
    configuration: string;
    display_data: string;
}

export interface ConfiguredProduct extends ProductData {
    price: number;
    configuration: DayConfiguration;
    display_data: DateConfiguration;
    available_quantity: number | string;
    volume_prices?: VolumePrices | {};
}

export interface VolumePrices {
    volume_prices: {
        quantity: number;
        net_price: number;
        gross_price: number;
    }[];
}

export interface DayConfiguration {
    time_of_day: number;
}

export interface DateConfiguration {
    'Preferred time of the day': string;
    Date: string;
}
