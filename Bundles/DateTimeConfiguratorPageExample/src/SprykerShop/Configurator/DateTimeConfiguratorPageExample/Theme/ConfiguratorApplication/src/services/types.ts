export interface VolumePrices {
    quantity: number;
    net_price: number;
    gross_price: number;
}

export interface MockDataItemDisabled {
    condition: string[];
    text: string;
}

export interface MockAvailabilityQuantity {
    condition: Partial<ProductData>;
    quantity: number;
}

export interface MockVolumePrices {
    condition: Partial<ProductData>;
    quantity: number;
}

export interface MockVolumePricesConfig {
    condition: Record<string, string>;
    prices: {
        GROSS_MODE: VolumePrices;
        NET_MODE: VolumePrices;
    };
}

export interface MockDataItem {
    value: string;
    title: string;
    price: number;
    disabled?: MockDataItemDisabled;
    availableQuantity?: number | MockAvailabilityQuantity[];
}

export interface MockData {
    id: string;
    label: string;
    tooltip: string;
    icon: string;
    data: MockDataItem[];
}

export interface MockProductInfo {
    name: string;
    image: string;
    logo: string;
    defaultPrice?: number;
}

export interface MockConfigurator {
    configuration: MockData[];
    data: MockProductInfo;
    defaults: Record<string, string>;
    volumePrices?: MockVolumePricesConfig[];
    debug?: boolean;
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
    available_quantity: number | null;
    volume_prices?: {
        volume_prices?: VolumePrices[];
    };
}
