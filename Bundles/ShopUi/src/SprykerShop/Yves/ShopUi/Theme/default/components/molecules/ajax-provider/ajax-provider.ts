import {debug, error} from '../../../app/logger';
import Component from '../../../models/component';

const EVENT_FETCHING = 'fetching';
const EVENT_FETCHED = 'fetched';

export default class AjaxProvider extends Component {
    protected isFetchingRequest: boolean = false
    readonly queryParams: Map<String, String> = new Map<String, String>()
    readonly headers: Map<String, String> = new Map<String, String>()

    readonly xhr: XMLHttpRequest

    constructor() {
        super();
        this.xhr = new XMLHttpRequest();
    }

    protected readyCallback(): void {
        if (this.fetchOnLoad) {
            this.fetch();
        }
    }

    async fetch<T = string>(data?: any): Promise<T> {
        debug(this.method, this.url, 'fetching...');
        this.isFetchingRequest = true;
        this.dispatchCustomEvent(EVENT_FETCHING);

        return new Promise<T>((resolve, reject) => {
            this.xhr.open(this.method, this.url);
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

        if (this.xhr.status !== 200) {
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

    get url(): string {
        const url = this.getAttribute('url');

        if (this.queryParams.size === 0) {
            return url;
        }

        const queryStringParams = [];

        this.queryParams.forEach((value: String, key: String) => {
            queryStringParams.push(`${key}=${value}`);
        });

        return url + '?' + queryStringParams.join('&');
    }

    get method(): string {
        return this.getAttribute('method').toUpperCase();
    }

    get responseType(): XMLHttpRequestResponseType {
        return <XMLHttpRequestResponseType>this.getAttribute('response-type');
    }

    get fetchOnLoad(): boolean {
        return this.hasAttribute('fetch-on-load');
    }

    get isFetching(): boolean {
        return this.isFetchingRequest;
    }
}
