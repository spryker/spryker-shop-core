import Component from 'ShopUi/models/component';
import sprite from './icon-sprite';

export default class IconLoader extends Component {
    protected svg: SVGElement;

    protected readyCallback(): void {}

    protected init(): void {
        this.svg = <SVGElement>this.getElementsByClassName(`${this.jsName}__svg`)[0];
        this.appendSprite();
    }

    protected appendSprite(): void {
        this.svg.innerHTML = sprite;
    }
}
