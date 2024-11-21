/* eslint-disable max-lines */
import debounce from 'lodash-es/debounce';
import Component from 'ShopUi/models/component';
import { BaseTraceableEventAdapter } from './base-traceable-event-adapter';

declare global {
    interface SPRYKER_EVENTS {
        PRODUCT_CLICK: undefined;
        FILTER_CLICK: undefined;
        PAGE_LOAD: undefined;
        ADD_TO_CART: undefined;
        ADD_TO_WISHLIST: undefined;
        ADD_TO_SHOPPING_LIST: undefined;
    }

    interface EventPages {
        CATALOG: undefined;
        CART: undefined;
        HOME: undefined;
        PDP: undefined;
        QUICK_ORDER: undefined;
        CHECKOUT_SUCCESS: undefined;
    }

    interface GlobalEventsData {
        page?: keyof EventPages;
    }
}

export type EventName = keyof SPRYKER_EVENTS;

export interface EventTriggerData {
    attribute?: string;
    flatten?: boolean;
    selector?: string | 'self';
    value: unknown;
    multi?: boolean;
    composed: Record<string, EventTriggerData | EventTriggerData[]>;
}

export type EventTriggerDataDetails = Record<string, EventTriggerData | EventTriggerData[]>;

export interface EventTriggerDataGroupAs {
    key: string;
    toArray?: boolean;
}

export interface EventTrigger {
    selector: string;
    data: EventTriggerDataDetails;
    groupAs?: EventTriggerDataGroupAs;
}

export interface EventData<T extends EventName> {
    event: T;
    name: string;
    triggers?: EventTrigger[];
}

export interface TraceableEvents {
    list: EventData<EventName>[];
    data: GlobalEventsData;
}

export interface EventsHandlerData<T extends EventName> extends Pick<EventData<T>, 'event'>, GlobalEventsData {
    [key: string]: unknown;
}

export type TraceableEventHandler = (data: EventsHandlerData<EventName>) => Promise<void> | void;

interface ValueDetails {
    composed?: EventTriggerDataDetails;
    value?: unknown;
    attribute: string;
    isElementAttribute?: boolean;
}

export default class TraceableEventsOrchestrator extends Component {
    protected groupedEvents: Partial<Record<string, EventData<EventName>[]>> = {};
    protected adapters: BaseTraceableEventAdapter[] = [];
    protected traceableEvents: TraceableEvents;
    protected initialHandlers = 0;
    protected debounceDelay = 300;
    protected controller = new AbortController();

    constructor() {
        super();

        this.initialHandlers = document.querySelectorAll(this.adapterSelector).length;
        this.traceableEvents = JSON.parse(this.events);
        this.groupedEvents = Object.groupBy(this.traceableEvents.list, ({ name }) => name);
    }

    protected readyCallback(): void {}
    protected init(): void {
        Object.keys(this.groupedEvents).forEach((name) => {
            document.addEventListener(
                name,
                debounce((event) => this.eventHandler(event), this.debounceDelay, { leading: true, trailing: false }),
                { signal: this.controller.signal },
            );
        });
    }

    disconnectedCallback() {
        this.controller.abort();
    }

    addAdapter(adapter: BaseTraceableEventAdapter): void {
        if (this.adapters.includes(adapter) || this.initialHandlers < this.adapters.length) {
            return;
        }

        this.adapters.push(adapter);

        if (this.initialHandlers === this.adapters.length) {
            this.eventHandler();
        }
    }

    removeAdapter(adapter: BaseTraceableEventAdapter): void {
        this.adapters = this.adapters.filter((item) => item !== adapter);
    }

    protected async eventHandler(event?: Event): Promise<void> {
        const eventsData = this.groupedEvents[event ? event.type : 'load'];

        if ((!this.adapters.length || !eventsData) && !this.debug) {
            return;
        }

        const target = event?.target as HTMLElement;
        let eventData: EventData<EventName>;
        let trigger: EventTrigger;
        let container: HTMLElement;

        for (const _eventData of eventsData) {
            trigger = _eventData.triggers?.find((_trigger) => {
                container = target.closest(_trigger.selector);

                return container;
            });

            const noEvent = !trigger && (Boolean(event) || _eventData.triggers);

            if (noEvent) {
                continue;
            }

            eventData = _eventData;

            break;
        }

        if (!eventData) {
            return;
        }

        const initialData = { event: eventData.event, ...this.traceableEvents.data };
        const dynamicData = this.generateDynamicData(container, trigger?.data);
        const data = { ...initialData, ...this.transformData(dynamicData, trigger?.groupAs) };

        if (this.debug) {
            /* eslint-disable no-console */
            console.group('TraceableEvents: eventHandler');
            console.info('Event Data:', eventData);
            console.info('Trigger Data:', trigger);
            console.info('Adapter Data:', data);
            console.groupEnd();
            /* eslint-enable no-console */
        }

        try {
            await Promise.all(this.adapters.map((adapter) => adapter.eventsHandler(data)));
        } catch (error) {
            // eslint-disable-next-line no-console
            console.error(`TraceableEvents: eventHandler - ${error}`);
        }
    }

    protected generateDynamicData(container: HTMLElement, data: EventTriggerDataDetails = {}): Record<string, unknown> {
        return Object.entries(data).reduce((acc, [key, _item]) => {
            const items = Array.isArray(_item) ? _item : [_item];

            for (const item of items) {
                const {
                    value: predefinedValue,
                    selector: itemSelector,
                    attribute: attributeName,
                    multi,
                    flatten,
                    composed,
                } = item;
                const attribute = attributeName ?? key;
                const isSelfAttribute = itemSelector === 'self';
                const element = isSelfAttribute
                    ? container
                    : multi
                    ? Array.from(container.querySelectorAll<HTMLElement>(itemSelector))
                    : container.querySelector<HTMLElement>(itemSelector);

                if (!element && predefinedValue === undefined) {
                    continue;
                }

                if (this.debug && element) {
                    /* eslint-disable no-console */
                    console.group('TraceableEvents: generateDynamicData');
                    console.info(`element by ${itemSelector} selector`, element);
                    console.info('element was found from container', container);
                    console.groupEnd();
                    /* eslint-enable no-console */
                }

                const isElementAttribute = Boolean(isSelfAttribute || attributeName);
                const details = { value: predefinedValue, attribute, isElementAttribute, composed };
                const value = Array.isArray(element)
                    ? element.map((el) => this.getValue(el, details))
                    : this.getValue(element, details);
                const data = typeof value === 'string' ? this.parseString(value) : value;
                const shouldFlat = flatten && typeof data === 'object' && data !== null;

                return shouldFlat ? { ...acc, ...data } : { ...acc, [key]: data };
            }
        }, {});
    }

    protected getValue(element: HTMLElement, details: ValueDetails): unknown {
        const { composed, value, isElementAttribute, attribute } = details;

        if (composed) {
            return this.generateDynamicData(element, composed);
        }

        if (value !== undefined) {
            return value;
        }

        if (isElementAttribute) {
            return element[attribute] ?? element.getAttribute(attribute);
        }

        return element.textContent;
    }

    protected transformData(
        data: Record<string, unknown>,
        transform?: EventTriggerDataGroupAs,
    ): Record<string, unknown> {
        if (!transform) {
            return data;
        }

        return { [transform.key]: transform.toArray ? [data] : data };
    }

    protected parseString(value: string): unknown {
        value = value.trim();

        try {
            return JSON.parse(value);
        } catch {
            return value;
        }
    }

    protected get events(): string {
        return this.getAttribute('events');
    }

    protected get adapterSelector(): string {
        return this.getAttribute('adapter-selector');
    }

    protected get debug(): boolean {
        return this.hasAttribute('debug');
    }
}
