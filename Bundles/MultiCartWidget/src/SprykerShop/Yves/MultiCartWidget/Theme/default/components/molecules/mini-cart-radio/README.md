Represents a switcher for a list of shopping carts, which sets one of the carts active for checkout.

## Code sample

```
{% include molecule('mini-cart-radio', 'MultiCartWidget') with {
    data: {
        idCart: idCart,
        isDefault: isDefault,
    }
} only %}
```
