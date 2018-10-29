# select (atom)

Creates a simple, single-selection drop-down element.

## Code sample

```
{% include atom('select') with {
    data: {
        options: options
    },
    attributes: {
        name: 'name',
        disabled: false
    }
} only %}
```
