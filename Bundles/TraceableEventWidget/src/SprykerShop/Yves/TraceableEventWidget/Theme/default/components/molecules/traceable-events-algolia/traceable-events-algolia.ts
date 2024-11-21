/* eslint-disable max-lines */
import algolia, { InsightsMethodMap } from 'search-insights';
import {
    BaseTraceableEventAdapter,
    TraceableEventHandlers,
} from '../traceable-events-orchestrator/base-traceable-event-adapter';
import {
    EventName,
    EventsHandlerData,
    TraceableEventHandler,
} from '../traceable-events-orchestrator/traceable-events-orchestrator';

declare global {
    interface GlobalEventsData {
        currency: string;
    }
}

interface InitialData {
    appId: string;
    apiKey: string;
    indexName: string;
    authenticatedUserToken?: string;
    userToken: string;
}

interface ProductDetails {
    sku: string;
    position?: number;
    price?: number;
    searchId?: string;
    quantity?: number;
}

interface Filter {
    name: string;
    value: string | string[];
}

interface ProductEventData extends EventsHandlerData<EventName> {
    products: ProductDetails[];
    searchId?: string;
    filters?: Filter[];
}

interface OrderSuccessData extends EventsHandlerData<EventName> {
    products: ProductDetails[];
    total: number;
}

const SEARCH_QUERY_ID_KEY = 'algolia-search-query-id';
const FILTERS_KEY = 'algolia-filters';

export default class TraceableEventsAlgolia extends BaseTraceableEventAdapter {
    protected algoliaLimitProducts = 20;
    protected algoliaLimitFilters = 10;

    override init(): void {
        const { appId, apiKey, userToken, authenticatedUserToken } = this.initialData;

        algolia('init', {
            appId,
            apiKey,
            ...((authenticatedUserToken || userToken) && { userToken: authenticatedUserToken || userToken }),
            ...(authenticatedUserToken && { authenticatedUserToken }),
        });

        super.init();
    }

    override getHandlers(): Partial<TraceableEventHandlers> {
        return {
            PRODUCT_CLICK: [this.productClickHandler],
            FILTER_CLICK: [this.filterClickHandler],
            PAGE_LOAD: [this.viewedProductsHandler, this.purchaseHandler, this.viewedFiltersHandler],
            ADD_TO_CART: [this.addToCartHandler],
            ADD_TO_WISHLIST: [this.convertedClickHandler('Added to Wishlist')],
            ADD_TO_SHOPPING_LIST: [this.convertedClickHandler('Added to Shopping List')],
        };
    }

    protected productClickHandler(data: ProductEventData): void {
        const { products, page } = data;
        const { sku, position, searchId: queryID } = products?.[0] ?? {};
        const name = queryID ? 'clickedObjectIDsAfterSearch' : 'clickedObjectIDs';

        this.sendEvent(name, {
            eventName: this.normalizeEventName('Product Clicked', page),
            objectIDs: [String(sku)],
            ...(queryID && { queryID, positions: [position] }),
        });

        if (page === 'PDP') {
            window.sessionStorage.removeItem(FILTERS_KEY);
        }
    }

    protected filterClickHandler(data: ProductEventData): void {
        this.sendEvent('clickedFilters', {
            eventName: 'Filter Clicked',
            filters: this.getFilters(data.filters),
        });
    }

    protected viewedProductsHandler(data: ProductEventData): void {
        const { products, page } = data;
        const validPages = ['PDP', 'CATALOG'];

        if (!products?.length || !validPages.includes(page)) {
            return;
        }

        this.sendEvent('viewedObjectIDs', {
            eventName: this.normalizeEventName('Products Viewed', page),
            objectIDs: this.normalizeProducts(products).map(({ sku }) => sku),
        });
    }

    protected viewedFiltersHandler(data: ProductEventData): void {
        const { filters, page } = data;
        const keepFiltersFor = ['PDP', 'CATALOG'];

        if (!keepFiltersFor.includes(page)) {
            window.sessionStorage.removeItem(FILTERS_KEY);
        }

        if (!filters?.length || page !== 'CATALOG') {
            return;
        }

        const parsedFilters = this.getFilters(filters);

        this.sendEvent('viewedFilters', {
            eventName: 'Filters Viewed',
            filters: parsedFilters,
        });
        window.sessionStorage.setItem(FILTERS_KEY, JSON.stringify(parsedFilters));
    }

    protected convertedClickHandler(eventName: string): TraceableEventHandler {
        return (data: ProductEventData): void => {
            const { products, searchId: queryID, page } = data;
            const name = queryID ? 'convertedObjectIDsAfterSearch' : 'convertedObjectIDs';

            this.sendEvent(name, {
                eventName: this.normalizeEventName(eventName, page),
                objectIDs: this.normalizeProducts(products).map(({ sku }) => sku),
                ...(queryID && { queryID }),
            });

            const filters = window.sessionStorage.getItem(FILTERS_KEY);

            if (!filters) {
                return;
            }

            this.sendEvent('convertedFilters', {
                eventName: `Filters Converted: ${eventName}`,
                filters: JSON.parse(filters),
            });
        };
    }

    protected addToCartHandler(data: ProductEventData): void {
        const { searchId: globalQueryID, currency, products = [], page } = data;
        const name = globalQueryID ? 'addedToCartObjectIDsAfterSearch' : 'addedToCartObjectIDs';
        const eventData = {
            eventName: this.normalizeEventName('Added to Cart', page),
            objectIDs: [],
            currency,
            ...(globalQueryID && { queryID: globalQueryID }),
            objectData: [],
            value: 0,
        };

        for (const product of this.normalizeProducts(products)) {
            const { sku, price, quantity, searchId } = product;
            const queryID = searchId === undefined ? globalQueryID : searchId;

            if (queryID) {
                const queries = { ...this.searchQueries };
                queries[sku] = queryID;
                sessionStorage.setItem(SEARCH_QUERY_ID_KEY, JSON.stringify(queries));
                eventData.queryID = queryID;
            }

            eventData.objectData.push({ price, quantity });
            eventData.objectIDs.push(sku);
            // eslint-disable-next-line @typescript-eslint/no-magic-numbers
            eventData.value = parseFloat((eventData.value + price * quantity).toFixed(2));
        }

        this.sendEvent(name, eventData);
    }

    protected purchaseHandler(data: OrderSuccessData): void {
        if (data.page !== 'CHECKOUT_SUCCESS' || !data.products.length) {
            return;
        }

        let withSearchQuery = false;
        const { products, currency, total: value } = data;
        const eventData = {
            eventName: 'Purchased',
            objectIDs: [],
            objectData: [],
            currency,
            value: this.normalizePrice(value),
        };
        const queries = this.searchQueries;

        for (const product of this.normalizeProducts(products)) {
            const { sku, price, quantity } = product;
            const queryID = queries[sku];

            if (queryID && !withSearchQuery) {
                withSearchQuery = true;
            }

            eventData.objectData.push({
                quantity,
                price,
                ...(queryID && { queryID }),
            });
            eventData.objectIDs.push(sku);
        }

        const name = withSearchQuery ? 'purchasedObjectIDsAfterSearch' : 'purchasedObjectIDs';

        this.sendEvent(name, eventData);
        sessionStorage.removeItem(SEARCH_QUERY_ID_KEY);
    }

    protected sendEvent(name: keyof InsightsMethodMap, data: Record<string, unknown>): void {
        algolia(name, {
            index: this.initialData.indexName,
            ...data,
        });
    }

    protected getFilters(_filters: Filter[]): string[] {
        const filtersMapper = this.filtersMapper;
        const filters: string[] = [];

        for (const filter of _filters) {
            const mappedKey = filtersMapper[filter.name];

            if (mappedKey === '') {
                continue;
            }

            const key = (mappedKey ?? filter.name).replace('[]', '');
            const data = Array.isArray(filter.value)
                ? filter.value.map((value) => `${key}:${value}`)
                : [`${key}:${filter.value}`];

            filters.push(...data);
        }

        return filters.slice(0, this.algoliaLimitFilters);
    }

    protected normalizeProducts(products: ProductDetails[]): ProductDetails[] {
        const _products: ProductDetails[] = [];
        const mapper: Record<string, number> = {};

        for (const product of products) {
            const { sku, price } = product ?? {};

            if (!sku) {
                continue;
            }

            product.quantity = Number(product.quantity ?? 1);

            if (_products.length >= this.algoliaLimitProducts) {
                break;
            }

            if (mapper[sku] === undefined) {
                mapper[sku] = _products.length;
                product.price = this.normalizePrice(price);
                _products.push(product);

                continue;
            }

            const index = mapper[sku];
            _products[index].quantity += product.quantity;
        }

        return _products;
    }

    protected normalizePrice(price?: unknown): number {
        if (typeof price !== 'number') {
            return undefined;
        }

        // eslint-disable-next-line @typescript-eslint/no-magic-numbers
        return Number.isInteger(price) ? price / 100 : price;
    }

    protected normalizeEventName(eventName: string, page = ''): string {
        return page ? `${page.replaceAll('_', ' ')}: ${eventName}` : eventName;
    }

    protected get searchQueries(): Record<string, string> {
        return JSON.parse(sessionStorage.getItem(SEARCH_QUERY_ID_KEY) ?? '{}');
    }

    protected get initialData(): InitialData {
        return JSON.parse(this.getAttribute('initial'));
    }

    protected get filtersMapper(): Record<string, string> {
        return JSON.parse(this.getAttribute('filters-mapper'));
    }
}
