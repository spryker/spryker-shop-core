Displays product quantity field with a label and, optionally, the update button for this field.

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
