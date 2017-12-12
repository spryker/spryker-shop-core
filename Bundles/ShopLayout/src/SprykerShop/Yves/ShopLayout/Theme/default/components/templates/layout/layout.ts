import ComponentMixin from 'ShopUI/models/component';

export default class Layout extends ComponentMixin(HTMLElement) {
    constructor() {
        super();
        
        console.log('this is a layout test');
    }
}
