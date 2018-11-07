# mini-cart-detail (molecule)

Displays a drop-down at the top navigation with list of shopping carts, which includes cart name, number of items in cart, price mode, and cart price.

## Code sample

```
{% include molecule('mini-cart-detail', 'MultiCartWidget') with {
    data: {
        cart: cart
    }
} only %}
```
