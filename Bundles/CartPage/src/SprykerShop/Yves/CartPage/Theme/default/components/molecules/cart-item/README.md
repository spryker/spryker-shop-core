# cart-item (molecule)

Displays detailed product information: product image, product name, product sku, drop-down with product variants, list of bundles, price, packaging unit information, quantity input, "remove" link and a form with note.

## Code sample

```
{% include molecule('cart-item', 'CartPage') with {
    data: {
        cart: cart,
        cartItem: cartItem,
        attributes: attributes
    }
} only %}
```
