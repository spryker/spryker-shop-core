# action-single-click-enforcer (molecule)

Prohibits double click event on specific target (array of DOM-elements).

## Code sample

```
{% include molecule('action-single-click-enforcer') with {
    attributes: {
        'target-selector': 'target-selector'
    }
} only %}
```
