Displays the number of items in a product cart.

## Code sample

```
{% include molecule('cart-items-counter') with {
    data: {
        label: 'label',
        quantity: quantity
    }
} only %}
```
