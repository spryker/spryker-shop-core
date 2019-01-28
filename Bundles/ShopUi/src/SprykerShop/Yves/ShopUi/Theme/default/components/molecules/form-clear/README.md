Clears form field values when a custom event is triggered (e.g. clicks a certain button).

## Code sample

```
{% include molecule('form-clear') with {
    attributes: {
        'form-selector': '.js-form-clear__' ~ data.formType ~ '-form',
        'trigger-selector': '.js-from-clear__' ~ data.formType ~ '-toggler',
        'ignore-selector': '.js-form-clear__ignore-element'
    }
} only %}
```
