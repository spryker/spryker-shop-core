# cart-table-detail (molecule)

Displays cart table detail row.

## Code sample

```
{% include molecule('cart-table-detail', 'MultiCartWidget') with {
    data: {
        cart: cart,
        actions: actions
    }
} only %}
```
