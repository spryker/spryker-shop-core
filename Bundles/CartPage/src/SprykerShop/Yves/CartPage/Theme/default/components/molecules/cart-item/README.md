Displays detailed product information: product image, product name, product SKU, drop-down menu with product variants, list of bundles, price, packaging unit information, quantity input, "delete" link, and a comment box.

## Code sample

```
{% include molecule('cart-item', 'CartPage') with {
    data: {
        cart: cart,
        isQuoteEditable: isQuoteEditable,
        cartItem: cartItem,
        attributes: attributes
    }
} only %}
```
