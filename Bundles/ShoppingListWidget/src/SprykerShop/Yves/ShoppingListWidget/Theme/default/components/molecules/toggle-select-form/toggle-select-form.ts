import Component from 'ShopUi/models/component';

export default class ToggleSelectForm extends Component {
    protected trigger: HTMLSelectElement;
    protected targets: HTMLElement[];

    protected readyCallback(): void {}

    protected init(): void {
        this.trigger = <HTMLSelectElement>this.querySelector('[data-select-trigger]');
        this.targets = <HTMLElement[]>Array.from(document.getElementsByClassName(this.target));
        this.toggle();
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.trigger.addEventListener('change', (event: Event) => this.onTriggerClick(event));
    }

    protected onTriggerClick(event: Event): void {
        event.preventDefault();
        this.toggle();
    }

    protected toggle(isToggle: boolean = this.isSelected): void {
        this.targets.forEach((element: HTMLElement) => element.classList.toggle(this.classToToggle, isToggle));
    }

    protected get isSelected(): boolean {
        return this.trigger.value !== '';
    }

    protected get target(): string {
        return this.trigger.getAttribute('target');
    }

    protected get classToToggle(): string {
        return this.trigger.getAttribute('class-to-toggle');
    }
}
