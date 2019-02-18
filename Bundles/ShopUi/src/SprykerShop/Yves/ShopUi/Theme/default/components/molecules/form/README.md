Wraps the symfony form to render its elements in a specific way; consists of a title, a list of form elements, the "submit" and the "cancel" buttons.

## Code sample

```
{% include molecule('form') with {
    data: {
        form: form,
        enableStart: true,
        enableEnd: true,
        layout: {},
        options: {
            attr: {
                novalidate: 'novalidate'
            }
        },
        title: 'title
    }
} only %}
```
