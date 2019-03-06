Creates a checkbox that adds/removes a class to/from the DOM-elements specified by the selector on check/uncheck.

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
