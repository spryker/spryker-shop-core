Displays a list of product variants as drop-down menu options.

## Code sample

```
{% include molecule('cart-item-variant-selector', 'CartPage') with {
    data: {
        cartItem: cartItem,
        cartItemAttributes: cartItemAttributes
    }
} only %}
```
