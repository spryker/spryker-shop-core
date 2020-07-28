import Component from 'ShopUi/models/component';

export default class StyleLoader extends Component {
    protected readyCallback(): void {}

    protected init(): void {
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.mapLoadEvent();
    }

    protected mapLoadEvent(): void {
        window.addEventListener('load', () => this.addCss());
    }

    protected addCss(): void {
        var link = document.createElement('link');
        link.href = this.pathToCSS;
        link.rel = 'stylesheet';

        document.getElementsByTagName('head')[0].appendChild(link);
    }

    protected get pathToCSS(): string {
        return this.getAttribute('path-to-css');
    }
}
