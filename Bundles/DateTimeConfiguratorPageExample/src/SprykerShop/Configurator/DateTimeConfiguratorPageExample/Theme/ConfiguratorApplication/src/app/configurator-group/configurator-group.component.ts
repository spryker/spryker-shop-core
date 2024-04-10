import { ChangeDetectionStrategy, Component, EventEmitter, Input, Output } from '@angular/core';
import { MockData, MockDataItem } from 'src/services/types';

@Component({
    selector: 'app-configurator-group',
    templateUrl: './configurator-group.component.html',
    styleUrls: ['./configurator-group.component.scss'],
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class ConfiguratorGroupComponent {
    @Input() currency: string;
    @Input() group: MockData;
    @Input() configuration: Record<string, string>;
    @Output() onChange = new EventEmitter<string>();

    isDisabled(item: MockDataItem): string | null {
        if (!item.disabled) {
            return null;
        }

        return Object.entries(item.disabled).find(([key, value]) =>
            (value.condition as string[]).includes(this.configuration[key]),
        )?.[1]?.tooltip;
    }

    isChecked(item: MockDataItem): boolean {
        return this.configuration[this.group.id] === item.value;
    }
}
