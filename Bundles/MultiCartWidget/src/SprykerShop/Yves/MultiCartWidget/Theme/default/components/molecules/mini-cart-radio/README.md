# mini-cart-radio (molecule)

Displays as a switcher for list of shopping carts, which sets an active cart for checkout.

## Code sample

```
{% include molecule('mini-cart-radio', 'MultiCartWidget') with {
    data: {
        idCart: idCart,
        isDefault: isDefault,
    }
} only %}
```
