import Component from 'ShopUi/models/component';
import AjaxProvider from '../ajax-provider/ajax-provider';

const TAG_NAME = 'form';

/**
 * When data-form-data-url-builder attribute is assigned to the form while submitting to the url will be added query parameters as key value pairs from form fields.
 *
 * @example
 *
 * <form action="url-path" data-form-data-url-builder>
 *   <input name="a" value="A">
 *   <input name="b" value="B">
 *
 * url will be "url-path?a=A&b=B"
 */
const AJAX_FORM_DATA_URL_BUILDER = 'data-form-data-url-builder';

/**
 * It's possible to skip some field from the form data url builder by adding data-form-data-url-builder-skip-field attribute to the submit element with field name.
 *
 * @example
 *
 * <form action="url-path" data-form-data-url-builder>
 *   <input name="a" value="A">
 *   <input name="b" value="B">
 *   <input name="c" value="C">
 *
 *   <button data-form-data-url-builder-skip-field="c">Submit</button>
 *
 * url will be "url-path?b=B&c=C"
 */
const AJAX_FORM_DATA_URL_BUILDER_SKIP_FIELD = 'data-form-data-url-builder-skip-field';

interface SubmitProps {
    form: HTMLFormElement;
    trigger: HTMLButtonElement | HTMLInputElement;
}

export default class FormSubmitter extends Component {
    protected provider: AjaxProvider;

    protected readyCallback(): void {}

    protected init(): void {
        this.provider = <AjaxProvider>document.querySelector(`.${this.providerClassName}`);

        this.mapEvents();
    }

    protected mapEvents(): void {
        for (const event of this.eventName.split(':')) {
            this.parentElement?.addEventListener(event, (_event: Event) => this.onEvent(_event));
        }
    }

    protected onEvent(event: Event): void {
        const trigger = (event.target as HTMLElement).closest(`[${this.triggerAttribute}]`) as
            | HTMLButtonElement
            | HTMLInputElement;
        const eventFromTrigger = trigger?.getAttribute(this.triggerAttribute);
        const isProperEvent = eventFromTrigger ? eventFromTrigger === event.type : true;

        if (!trigger || (trigger && !isProperEvent)) {
            return;
        }

        event.preventDefault();

        const form = <HTMLFormElement>trigger.closest(TAG_NAME);

        if (!form) {
            return;
        }

        this.onAjaxSubmit({ form, trigger });
    }

    protected onAjaxSubmit({ form, trigger }: SubmitProps): void {
        const formData = new FormData(form);
        const url = trigger.getAttribute('formaction') ?? form.action;
        let parts = '';

        if (form.hasAttribute(AJAX_FORM_DATA_URL_BUILDER)) {
            const url = new URL(window.location.href);
            const searchParams = new URLSearchParams();
            const skipFields = trigger.getAttribute(AJAX_FORM_DATA_URL_BUILDER_SKIP_FIELD) ?? '';

            for (const data of [...(formData as unknown as { entries: () => [string, string] }).entries()]) {
                if (data[0] === skipFields) {
                    continue;
                }

                parts += `${data[0]}=${data[1]}&`;
                searchParams.set(data[0], data[1]);
            }

            url.search = searchParams.toString();
            window.history.replaceState({}, '', url.href);
        }

        const query = parts ? `?${parts}` : '';

        this.provider.setAttribute('url', `${url}${query}`);

        if (form.method) {
            this.provider.setAttribute('method', form.method);
        }

        this.provider.fetch(formData);
    }

    protected get triggerAttribute(): string {
        return this.getAttribute('trigger-attribute');
    }

    protected get providerClassName(): string {
        return this.getAttribute('provider-class-name');
    }

    protected get eventName(): string {
        return this.getAttribute('event');
    }
}
