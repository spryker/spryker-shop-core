import { ChangeDetectionStrategy, Component, ViewEncapsulation } from '@angular/core';

import { ConfiguratorService } from '../services/configurator.service';

@Component({
    selector: 'app-root',
    templateUrl: './app.component.html',
    styleUrls: ['./app.component.scss'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
})
export class AppComponent {
    constructor(private configuratorService: ConfiguratorService) {}

    loading$ = this.configuratorService.loading$;
}
