# toggler-hash (molecule)

Provides functionality to add/remove class to/from an array of DOM-elements if browser address input field includes or doesn't include the same hash as in a component.

## Code sample

```
{% include molecule('toggler-hash') with {
    attributes: {
        'trigger-hash': 'trigger-hash',
        'target-selector': 'target-selector',
        'class-to-toggle': 'class-to-toggle',
        'add-class-when-hash-in-url': false
    }
} only %}
```
