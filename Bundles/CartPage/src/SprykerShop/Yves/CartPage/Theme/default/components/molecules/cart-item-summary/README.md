# cart-item-summary (molecule)

Displays product summary information: price, price for different options, total and subtotal.

## Code sample

```
{% include molecule('cart-item-summary', 'CartPage') with {
    data: {
        unitPrice: 'unitPrice',
        subtotalPrice: 'subtotalPrice',
        cartItem: cartItem
    }
} only %}
```
