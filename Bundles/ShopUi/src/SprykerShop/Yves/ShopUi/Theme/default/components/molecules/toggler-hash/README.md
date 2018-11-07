# toggler-hash (molecule)

Initiates functionality to add/remove class to/from some array of DOM-elements if browser address input field consist or doesn't consist same hash as in component.

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
