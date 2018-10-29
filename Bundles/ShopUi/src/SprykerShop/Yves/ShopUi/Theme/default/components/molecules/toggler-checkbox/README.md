# toggler-checkbox (molecule)

Creates checkbox which add/remove class to some DOM-elements specified by selector on check/uncheck.

## Code sample

```
{% include molecule('toggler-checkbox') with {
    data: {
        type: 'type'
    },
    attributes: {
        'target-selector': 'target-selector',
        'class-to-toggle': 'class-to-toggle',
        checked: true
    }
} only %}
```
