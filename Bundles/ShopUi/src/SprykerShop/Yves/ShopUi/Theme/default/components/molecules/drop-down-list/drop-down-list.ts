import Component from '../../../models/component';

export default class DropDownList extends Component {
    protected triggerButton: HTMLElement;
    protected targetList: HTMLElement;

    protected readyCallback() {}

    protected init() {
        this.triggerButton = <HTMLElement>this.getElementsByClassName(`${this.jsName}__button`)[0];
        this.targetList = <HTMLElement>this.getElementsByClassName(`${this.jsName}__list`)[0];

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.triggerButton.addEventListener('click', () => this.onClickHandler());
    }

    protected onClickHandler(): void {
        this.targetList.classList.toggle(this.listTriggerClass);
        this.triggerButton.classList.toggle(this.buttonTriggerClass);
    }

    protected get listTriggerClass(): string {
        return this.getAttribute('list-trigger-class');
    }

    protected get buttonTriggerClass(): string {
        return this.getAttribute('button-trigger-class');
    }
}
