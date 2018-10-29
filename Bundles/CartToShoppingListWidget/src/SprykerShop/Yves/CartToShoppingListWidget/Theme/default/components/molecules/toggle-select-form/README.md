# toggle-select-form (molecule)

Toggle class for specified DOM-elements on target change event.

## Code sample

```
{% include molecule('toggle-select-form', 'CartToShoppingListWidget') with {
    data: {
        attribute: attribute,
        selectAttributes: {
            'data-select-trigger': 'data-select-trigger',
            'target': 'target',
            'class-to-toggle': 'class-to-toggle'
        }
    }
} only %}
```
