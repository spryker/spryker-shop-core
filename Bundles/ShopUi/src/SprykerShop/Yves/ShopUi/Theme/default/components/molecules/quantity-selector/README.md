# quantity-selector (molecule)

Displays a drop-down with predefined values of product quantity.

## Code sample

```
{% include molecule('quantity-selector') with {
    data: {
        maxQuantity: maxQuantity,
        step: step
    }
} only %}
```
