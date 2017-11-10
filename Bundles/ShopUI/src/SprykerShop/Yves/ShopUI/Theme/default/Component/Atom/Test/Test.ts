import ComponentMixin from '../../../Mixin/component';

export default class AtomTest extends ComponentMixin(HTMLElement) {
    constructor() { 
        super();
        console.log('this is a test');
    }
}
