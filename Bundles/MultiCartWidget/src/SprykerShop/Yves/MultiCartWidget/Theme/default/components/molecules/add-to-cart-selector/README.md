# add-to-cart-selector (molecule)

Displays a drop-down menu with a list of shopping carts.

## Code sample

```
{% include molecule('add-to-cart-selector', 'MultiCartWidget') with {
    data: {
        carts: carts,
        disabled: false
    }
} only %}
```
