import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { map } from 'rxjs/operators';
import { ProductData } from './types';

@Injectable()
export class ProductService {
    productData: ProductData;
    token: string;

    constructor(private http: HttpClient) {
        this.token = this.getToken();
    }

    getData(): Observable<ProductData> {
        return this.http.get<{data: ProductData}>('/', {
            params: { 'getConfigurationByToken': this.token }
        }).pipe(map((response) => {
            this.productData = response.data;

            return this.productData;
        }));
    }

    sendData(data: FormData): Observable<ProductData> {
        return this.http.post<ProductData>('/', data, { params: { prepareConfiguration: this.token } });
    }

    private getToken(): string {
        const locationSearchArr = location.search.split('=');

        return locationSearchArr[locationSearchArr.length - 1];
    }
}
