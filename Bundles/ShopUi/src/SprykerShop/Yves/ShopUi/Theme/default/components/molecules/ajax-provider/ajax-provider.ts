import {debug, error} from '../../../app/logger';
import Component from '../../../models/component';

const EVENT_FETCHING = 'fetching';
const EVENT_FETCHED = 'fetched';

/**
 * @event fetching An event which is triggered when an ajax request is sent to the server.
 * @event fetched An event which is triggered when an ajax request is closed.
 */
export default class AjaxProvider extends Component {
    protected isFetchingRequest: boolean = false;

    /**
     * Defines the key/value pairs which are send with the request as query parameters.
     *
     * @remarks
     * Use it to add/remove query parameters from the request.
     */
    readonly queryParams: Map<string, string> = new Map<string, string>();

    /**
     * Defines the key/value pairs which are send with the request as headers.
     *
     * @remarks
     * Use it to add/remove headers from the request.
     */
    readonly headers: Map<string, string> = new Map<string, string>();

    /**
     * Represents the request object used by the component to perform the fetch operation.
     */
    readonly xhr: XMLHttpRequest;
    protected xhrStatusSuccessOk: number = 200;

    constructor() {
        super();
        this.xhr = new XMLHttpRequest();
    }

    protected readyCallback(): void {
        if (this.fetchOnLoad) {
            this.fetch();
        }
    }

    /**
     * Sends an ajax request to the server.
     * @template T The argument type returned by a successful promise.
     * @param data Optional data sent to the server in the request body.
     * @returns A generic typed promise connected to the ajax request.
     */
    /* tslint:disable: no-any */
    async fetch<T = string>(data?: any): Promise<T> {
        /* tslint:enable */
        debug(this.method, this.url, 'fetching...');
        this.isFetchingRequest = true;
        this.dispatchCustomEvent(EVENT_FETCHING);

        return new Promise<T>((resolve, reject) => {
            this.xhr.open(this.method, this.url);
            this.headers.forEach((value: string, key: string) => this.xhr.setRequestHeader(key, value));
            this.xhr.responseType = this.responseType;
            this.xhr.addEventListener('load', (event: Event) => this.onRequestLoad(resolve, reject, event));
            this.xhr.addEventListener('error', (event: Event) => this.onRequestError(reject, event));
            this.xhr.addEventListener('abort', (event: Event) => this.onRequestAbort(reject, event));
            this.xhr.send(data);
        });
    }

    protected onRequestLoad(resolve: Function, reject: Function, loadEvent: Event): void {
        this.isFetchingRequest = false;
        this.dispatchCustomEvent(EVENT_FETCHED);

        if (this.xhr.status !== this.xhrStatusSuccessOk) {
            reject(new Error(`${this.method} ${this.xhr.status} ${this.url} server error`));

            return;
        }

        debug(this.method, this.xhr.status, this.url);
        resolve(this.xhr.response);
    }

    protected onRequestError(reject: Function, errorEvent: Event): void {
        this.isFetchingRequest = false;
        this.dispatchCustomEvent(EVENT_FETCHED);
        reject(new Error(`${this.method} ${this.url} request error`));
    }

    protected onRequestAbort(reject: Function, abortEvent: Event): void {
        this.isFetchingRequest = false;
        this.dispatchCustomEvent(EVENT_FETCHED);
        reject(new Error(`${this.method} ${this.url} request aborted`));
    }

    /**
     * Gets the url endpoint used to perform the ajax call to.
     */
    get url(): string {
        const url = this.getAttribute('url');

        if (this.queryParams.size === 0) {
            return url;
        }

        const queryStringParams = [];

        this.queryParams.forEach((value: string, key: string) => {
            const encodeValue = encodeURIComponent(value);
            queryStringParams.push(`${key}=${encodeValue}`);
        });

        return `${url}?${queryStringParams.join('&')}`;
    }

    /**
     * Gets the request method.
     */
    get method(): string {
        return this.getAttribute('method').toUpperCase();
    }

    /**
     * Gets the response type.
     */
    get responseType(): XMLHttpRequestResponseType {
        return <XMLHttpRequestResponseType>this.getAttribute('response-type');
    }

    /**
     * Gets if the component performs the fetch operation after loading.
     */
    get fetchOnLoad(): boolean {
        return this.hasAttribute('fetch-on-load');
    }

    /**
     * Gets if the component is currently fetching.
     */
    get isFetching(): boolean {
        return this.isFetchingRequest;
    }
}
