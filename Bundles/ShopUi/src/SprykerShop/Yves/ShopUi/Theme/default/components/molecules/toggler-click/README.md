# toggler-click (molecule)

Initiates functionality to handle clicks on the array of DOM-elements to toggle the specific class on another array of DOM-elements.

## Code sample

```
{% include molecule('toggler-click') with {
    attributes: {
        'trigger-selector': 'trigger-selector',
        'target-selector': 'target-selector',
        'class-to-toggle': 'class-to-toggle'
    }
} only %}
```
