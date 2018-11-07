# shared-cart-customers-table (molecule)

Displays a shared carts as table, which you can unshare fron another castomers or business units.

## Code sample

```
{% include molecule('shared-cart-customers-table', 'SharedCartWidget') with {
    data: {
        cart: cart
    }
} only %}
```
