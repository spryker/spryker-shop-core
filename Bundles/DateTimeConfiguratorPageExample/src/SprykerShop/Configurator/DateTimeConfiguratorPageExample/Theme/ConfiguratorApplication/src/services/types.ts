export interface MockDataItemDisabled {
    condition: string[];
    text: string;
}

export interface MockDataItem {
    value: string;
    title: string;
    price: number;
    disabled?: MockDataItemDisabled;
}

export interface MockData {
    id: string;
    label: string;
    tooltip: string;
    icon: string;
    data: MockDataItem[];
}

export interface MockConfigurator {
    configuration: MockData[];
    data: {
        name: string;
        image: string;
        logo: string;
    };
    defaults: Record<string, string>;
}

export interface ProductMetaData {
    timestamp?: string;
    checkSum?: string;
}

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
}

export interface ServerData extends ProductData {
    configuration: string;
    display_data: string;
}

export interface ConfiguredProduct extends ProductData {
    price: number;
    configuration: Record<string, string>;
    display_data: Record<string, string>;
    available_quantity: number | string;
    volume_prices?: VolumePrices | object;
}

export interface VolumePrices {
    volume_prices: Array<{
        quantity: number;
        net_price: number;
        gross_price: number;
    }>;
}
