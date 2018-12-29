# mini-cart-detail (molecule)

Displays a drop-down menu at the top navigation bar with the list of shopping carts, which includes cart name, number of items in cart, price mode, and cart total price.

## Code sample

```
{% include molecule('mini-cart-detail', 'MultiCartWidget') with {
    data: {
        cart: cart
    }
} only %}
```
