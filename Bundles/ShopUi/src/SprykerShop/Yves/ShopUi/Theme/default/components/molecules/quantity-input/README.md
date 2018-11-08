# quantity-input (molecule)

Displays a product quantity field with label and an optional update button (used to submit the quantity and update the parent view).

## Code sample

```
{% include molecule('quantity-input') with {
    data: {
        maxQuantity: maxQuantity,
        value: 'value',
        readOnly: false
    }
} only %}
```
