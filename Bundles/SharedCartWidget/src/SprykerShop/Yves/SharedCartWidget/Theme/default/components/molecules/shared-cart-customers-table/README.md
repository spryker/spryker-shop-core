# shared-cart-customers-table (molecule)

Displays shared cart customers table, with action(unshare).

## Code sample

```
{% include molecule('shared-cart-customers-table', 'SharedCartWidget') with {
    data: {
        cart: cart
    }
} only %}
```
