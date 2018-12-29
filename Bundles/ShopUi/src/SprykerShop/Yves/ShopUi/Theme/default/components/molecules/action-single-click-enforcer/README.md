# action-single-click-enforcer (molecule)

Prohibits the double-click event on a specific target (array of DOM-elements).

## Code sample

```
{% include molecule('action-single-click-enforcer') with {
    attributes: {
        'target-selector': 'target-selector'
    }
} only %}
```
