import ButtonsDisableStateHandler from '../buttons-disable-state-handler/buttons-disable-state-handler';

export default class ButtonsEnableStateHandler extends ButtonsDisableStateHandler {
    protected init(): void {
        super.init()
    }

    protected onTriggerChange(): void {
        const checkedTriggers = this.triggers.filter(checkbox => checkbox.checked);

        if (checkedTriggers.length) {
            this.enableTargets();

            return;
        }

        this.disableTargets();
    }
}
