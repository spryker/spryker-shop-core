# cart-table-detail (molecule)

Displays shopping cart details as a table row.

## Code sample

```
{% include molecule('cart-table-detail', 'MultiCartWidget') with {
    data: {
        cart: cart,
        actions: actions
    }
} only %}
```
