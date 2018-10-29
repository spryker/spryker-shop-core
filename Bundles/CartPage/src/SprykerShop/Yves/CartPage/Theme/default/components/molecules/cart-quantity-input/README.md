# cart-quantity-input (molecule)

Displays product quantity input which allows to set maximum quantity and "read only" state for this input, as well as "submit" button.

## Code sample

```
{% include molecule('cart-quantity-input', 'CartPage') with {
    data: {
        cartItem: cartItem,
        readOnly: false
    }
} only %}
```
