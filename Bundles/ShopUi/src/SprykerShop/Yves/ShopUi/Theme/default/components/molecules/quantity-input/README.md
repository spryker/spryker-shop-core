# quantity-input (molecule)

Displays a product quantity field with the label and the optionally update button for this field.

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
