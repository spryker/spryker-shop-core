# toggler-radio (molecule)

Creates a radio button which toggles classes for the related containers: when radio button of a specific container is selected, adds a class to it, at the same time removing the class from other radio button containers of the same radio button group.

## Code sample

```
{% include molecule('toggler-radio') with {
    data: {
        type: 'type'
    }
} only %}
```
