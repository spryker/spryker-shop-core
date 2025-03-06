import Component from '../../../models/component';

export default class InputDropzone extends Component {
    protected input: HTMLInputElement;
    protected filesContainer: HTMLElement;
    protected fileTemplate: HTMLTemplateElement;
    protected transfer = new DataTransfer();

    protected readyCallback(): void {}

    protected init(): void {
        this.transfer = new DataTransfer();
        this.input = this.querySelector(`.${this.jsName}__input`);
        this.filesContainer = this.querySelector(`.${this.jsName}__files`);
        this.fileTemplate = this.querySelector<HTMLTemplateElement>(`[data-id="${this.jsName}-file-template"]`);

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.input.addEventListener('change', this.onChange.bind(this));
        this.querySelector(`.${this.jsName}__area`)?.addEventListener('drop', this.onDrag.bind(this));
    }

    protected onChange(event: Event) {
        this.cancelEvent(event);
        this.setFiles((event.target as HTMLInputElement).files);
    }

    protected onDrag(event: DragEvent) {
        this.cancelEvent(event);
        this.setFiles(event.dataTransfer.files);
    }

    protected cancelEvent(event: Event) {
        event.preventDefault();
        event.stopPropagation();
    }

    protected setFiles(files: FileList) {
        for (const file of Array.from(files)) {
            if (this.transfer.files.length >= this.maxCount) {
                // eslint-disable-next-line no-console
                console.warn(`The maximum number of files is ${this.maxCount}`);
                break;
            }

            const isFileTypeAllowed = file.type.split('/').some((type) => this.acceptedFormats.includes(type));

            if (!isFileTypeAllowed || !file.type) {
                // eslint-disable-next-line no-console
                console.warn(`The file ${file.name} has an unsupported format`);
                continue;
            }

            this.transfer.items.add(file);
            this.addFileElement(file, this.transfer.files.length - 1);
        }

        this.input.files = this.transfer.files;
    }

    protected addFileElement(file: File, index: number): void {
        const clone = this.fileTemplate.content.cloneNode(true) as DocumentFragment;
        const fileElement = clone.querySelector(`.${this.jsName}__file`);
        const bytes = 1024;
        const decimal = 2;
        const size = (file.size / (bytes * bytes)).toFixed(decimal);

        clone.querySelector(`.${this.jsName}__file-name`).textContent = file.name;
        clone.querySelector(`.${this.jsName}__file-size`).textContent = size === '0.00' ? '< 0.01' : size;
        fileElement.addEventListener('click', () => this.deleteFile(fileElement, index));
        this.filesContainer.appendChild(clone);
    }

    protected deleteFile(element: Element, index: number): void {
        this.transfer.items.remove(index);
        this.input.files = this.transfer.files;
        element.remove();
    }

    protected get acceptedFormats(): string {
        return this.getAttribute('accept');
    }

    protected get maxCount(): number {
        return Number(this.getAttribute('max-count')) || Infinity;
    }
}
