Displays a "loading" indicator image (spinner) while an AJAX request sent via the related ajax-provider is being performed.

## Code sample

```
{% include molecule('ajax-loader') with {
    data: {
        label: 'label',
        showSpinnerOnLoad: false
    }
    attributes: {
        'provider-selector': 'provider-selector'
    }
} only %}
```
