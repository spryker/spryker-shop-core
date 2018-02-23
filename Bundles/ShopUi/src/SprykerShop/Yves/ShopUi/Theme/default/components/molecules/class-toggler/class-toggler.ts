import Component from '../../../models/component';

export default class ClassToggler extends Component {
    readonly trigger: HTMLInputElement
    readonly targets: HTMLElement[]

    constructor() { 
        super();
        this.trigger = <HTMLInputElement>this.querySelector(`.${this.selector}__trigger`);
        this.targets = <HTMLElement[]>Array.from(document.getElementsByClassName(this.target));
    }

    readyCallback(): void {
        this.toggle();
        this.fireToggleEvent();
        this.mapEvents();
    }

    mapEvents(): void {
        this.trigger.addEventListener('change', (event: Event) => this.onTargetClick(event));
    }

    onTargetClick(event: Event): void { 
        event.preventDefault();
        this.toggle();
        this.fireToggleEvent();
    }

    toggle(addClass: boolean = this.addClass): void { 
        this.targets.forEach((element: HTMLElement) => element.classList.toggle(this.classToToggle, addClass));
    }

    fireToggleEvent() { 
        const event = new CustomEvent('toggle');
        this.dispatchEvent(event);
    }

    get addClass(): boolean { 
        return this.addWhenChecked ? this.trigger.checked : !this.trigger.checked;
    }

    get target(): string {
        return this.trigger.getAttribute('target');
    }

    get classToToggle(): string {
        return this.trigger.getAttribute('class-to-toggle');
    }

    get addWhenChecked(): boolean {
        return this.trigger.hasAttribute('add-when-checked');
    }
}
