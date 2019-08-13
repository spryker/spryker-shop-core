import Component from 'ShopUi/models/component';

export default class CommentThreadList extends Component {
    protected commentThreadSelectComponent: HTMLElement;
    protected commentThread: HTMLElement[];

    protected readyCallback(): void {
        this.commentThreadSelectComponent = <HTMLElement>document.querySelector(this.commentThreadSelectSelector);
        this.commentThread = <HTMLElement[]>Array.from(this.querySelectorAll(`.${this.commentThreadSelector}`));
        this.show();
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.commentThreadSelectComponent.addEventListener('change', (event: Event) => {
            this.onSelectChange(event);
        });
    }

    protected show(): void {
        const commentThreadSelect: HTMLSelectElement = this.commentThreadSelectComponent.querySelector('select');
        this.onShowCommentThread(commentThreadSelect.value);
        this.scrollDown();
    }

    protected onSelectChange(event: Event): void {
        const commentThreadSelect: HTMLSelectElement = (event.target as HTMLSelectElement);
        this.onShowCommentThread(commentThreadSelect.value);
        this.scrollDown();
    }

    protected onShowCommentThread(showNameComment: string): void {
        this.commentThread.forEach((comment: HTMLElement) => {
            const commentName = comment.getAttribute('name');

            if (commentName !== showNameComment) {
                comment.classList.add(this.classToToggle);

                return;
            }
            comment.classList.remove(this.classToToggle);
        });
    }

    protected scrollDown(): void {
        if (this.scrollHeight > this.clientHeight) {
            this.scrollTop = this.scrollHeight - this.clientHeight;
        }
    }

    /**
     * Gets a querySelector name of the select element.
     */
    get commentThreadSelectSelector(): string {
        return this.getAttribute('comment-thread-select-selector');
    }

    /**
     * Gets a querySelector name of the comment-thread component.
     */
    get commentThreadSelector(): string {
        return this.getAttribute('comment-thread-selector');
    }

    /**
     * Gets a class name for the toggle action.
     */
    get classToToggle(): string {
        return this.getAttribute('class-to-toggle');
    }
}
