# form-input-default-value-disabler (molecule)

Disables form fields before submitting, if they have data-default-value attribute.

## Code sample

```
{% include molecule('form-input-default-value-disabler') with {
    attributes: {
        'form-selector': 'form-selector',
        'input-selector': 'input-selector',
        'default-value-attribute': 'default-value-attribute'
    }
} only %}
```
