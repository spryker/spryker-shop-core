# cart-item-variant-selector (molecule)

Displays a list of product variants as dropdowns.

## Code sample

```
{% include molecule('cart-item-variant-selector', 'CartPage') with {
    data: {
        cartItem: cartItem,
        cartItemAttributes: cartItemAttributes
    }
} only %}
```
