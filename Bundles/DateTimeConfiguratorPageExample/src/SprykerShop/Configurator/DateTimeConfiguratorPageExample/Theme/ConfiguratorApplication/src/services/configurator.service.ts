import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import {
    BehaviorSubject,
    Observable,
    combineLatest,
    map,
    merge,
    of,
    scan,
    shareReplay,
    startWith,
    switchMap,
    withLatestFrom,
} from 'rxjs';
import { environment } from '../environments/environment';
import { ProductService } from './product.service';
import { ConfiguredProduct, MockConfigurator, ServerData } from './types';

export const ASSETS = !environment.production ? '' : './dist';
const CONFIGURATOR = !environment.production ? `${ASSETS}/assets/data/configurator.json` : './configurator.json';

@Injectable({ providedIn: 'root' })
export class ConfiguratorService {
    constructor(private http: HttpClient, private product: ProductService) {}

    configuration$ = this.http.get<MockConfigurator>(CONFIGURATOR).pipe(shareReplay({ bufferSize: 1, refCount: true }));

    data$ = this.configuration$.pipe(map((data) => data.configuration));
    defaults$ = this.configuration$.pipe(map((data) => data.defaults));
    productData$ = this.configuration$.pipe(
        withLatestFrom(this.product.getData()),
        map(([data, product]) => ({
            ...data.data,
            ...product,
        })),
        shareReplay({ bufferSize: 1, refCount: true }),
    );

    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    setConfigurator$ = new BehaviorSubject<Partial<Record<string, any>>>({});

    configurator$: Observable<ConfiguredProduct & { price: number }> = this.product.getData().pipe(
        switchMap((_data: ServerData) => {
            const data = this.generateConfiguredData(_data);
            return this.setConfigurator$.pipe(
                startWith(data),
                scan((config, newConfig) => ({
                    ...config,
                    ...newConfig,
                    configuration: {
                        ...config.configuration,
                        ...newConfig.configuration,
                    },
                })),
                switchMap((configurator) => combineLatest([of(configurator), this.defaults$])),
                switchMap(([configurator, defaults]) => {
                    configurator.configuration = {
                        ...defaults,
                        // eslint-disable-next-line @typescript-eslint/no-explicit-any
                        ...(configurator.configuration as Record<string, any>),
                    };

                    return this.data$.pipe(
                        map((data) => {
                            const displayData: Record<string, string> = {};
                            const price = Object.entries(configurator.configuration).reduce((price, [key, value]) => {
                                const config = data.find((configs) => configs.id === key);
                                const active = config?.data?.find((options) => options.value === value);
                                const uncheck = active?.disabled
                                    ? Object.entries(active.disabled).some(([key, value]) =>
                                          (value.condition as string[]).includes(configurator.configuration[key]),
                                      )
                                    : null;

                                if (uncheck) {
                                    delete configurator.configuration[key];
                                }

                                if (active && !uncheck) {
                                    displayData[config.label] = active.title;
                                }

                                return active && !uncheck ? active.price + price : price;
                            }, 0);

                            return {
                                ...configurator,
                                display_data: displayData,
                                price,
                            } as ConfiguredProduct & { price: number };
                        }),
                    );
                }),
            );
        }),
        shareReplay({ bufferSize: 1, refCount: true }),
    );
    loading$ = merge(this.product.getData().pipe(map(() => true)), this.configurator$.pipe(map(() => false))).pipe(
        startWith(true),
    );
    dirty$ = combineLatest([this.configurator$, this.defaults$]).pipe(
        map(([data, defaults]) => JSON.stringify(data.configuration) !== JSON.stringify(defaults)),
    );

    private generateConfiguredData(response: ServerData): ConfiguredProduct {
        const configuration = JSON.parse(response.configuration);
        const displayData = JSON.parse(response.display_data);
        const productData = {
            ...response,
            ...({
                configuration,
                display_data: displayData,
            } as ConfiguredProduct),
        };

        return {
            ...productData,
            price: 0,
        };
    }

    updateConfiguratorConfiguration(data: Record<string, unknown>) {
        this.setConfigurator$.next({
            configuration: data,
        });
    }

    updateWithGeneratedProductData(newProductData): void {
        this.setConfigurator$.next(newProductData);
    }

    setDate(date: string): string {
        return date.split('-').reverse().join('.');
    }

    remove(propertyName: string, productData: ConfiguredProduct): void {
        delete productData[propertyName];

        this.updateWithGeneratedProductData(productData);
    }

    convertDate(date: string): string {
        return date.split('.').reverse().join('-');
    }
}
