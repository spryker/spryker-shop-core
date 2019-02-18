Displays the product quantity input that allows setting the maximum quantity and the "read-only" state for this input, as well as the "submit" button.

## Code sample

```
{% include molecule('cart-quantity-input', 'CartPage') with {
    data: {
        cartItem: cartItem,
        readOnly: false
    }
} only %}
```
