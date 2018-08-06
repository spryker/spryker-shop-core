export default class AjaxFetcher {
    protected xhr: XMLHttpRequest;
    protected isLoading: boolean;

    constructor() {
        this.xhr = new XMLHttpRequest();
    }

    public fetch(url: string, method: string = 'GET'): Promise<object> {
        if (this.isLoading) {
            return new Promise<object>((resolve, reject) => {
                reject()
            })
        }

        return new Promise<object>(((resolve, reject) => {
            this.isLoading = true;
            this.xhr.open(method, url);
            this.xhr.responseType= 'json';
            this.xhr.onload = () => {
                if (this.xhr.status === 200) {
                    resolve(this.xhr.response);
                } else {
                    reject(this.xhr.response);
                }

                this.isLoading = false;
            };

            this.xhr.onabort = this.xhr.onerror = () => {
                reject(this.xhr.response);
                this.isLoading = false;
            };

            this.xhr.send();
        }))
    }

    public get getIsLoading() {
        return this.isLoading;
    }
}
