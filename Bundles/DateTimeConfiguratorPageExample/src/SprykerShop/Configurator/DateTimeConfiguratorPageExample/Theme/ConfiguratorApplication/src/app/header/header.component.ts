import { ChangeDetectionStrategy, Component } from '@angular/core';
import { map, Subject, withLatestFrom } from 'rxjs';
import { ConfiguratorService } from 'src/services/configurator.service';
import { ProductService } from 'src/services/product.service';

@Component({
    selector: 'app-header',
    templateUrl: './header.component.html',
    styleUrls: ['./header.component.scss'],
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class HeaderComponent {
    constructor(protected data: ProductService, protected configuration: ConfiguratorService) {}

    title$ = this.configuration.productData$.pipe(map((data) => data.name));
    logo$ = this.configuration.productData$.pipe(map((data) => data.logo));
    modalTrigger$ = new Subject<boolean>();
    modal$ = this.modalTrigger$.pipe(
        withLatestFrom(this.configuration.dirty$),
        map(([status, dirty]) => {
            if (!dirty && status) {
                return this.backTrigger();
            }

            if (status) {
                document.documentElement.style.height = '100%';
                document.documentElement.style.overflow = 'hidden';
            } else {
                document.documentElement.style.height = '';
                document.documentElement.style.overflow = '';
            }

            return status;
        }),
    );

    modalTrigger(event: Event, value: boolean): void {
        event.preventDefault();
        this.modalTrigger$.next(value);
    }

    backTrigger(): void {
        history.back();
    }
}
