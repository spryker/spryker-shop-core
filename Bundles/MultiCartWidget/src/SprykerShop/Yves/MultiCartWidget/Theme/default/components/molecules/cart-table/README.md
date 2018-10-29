# cart-table (molecule)

Displays cart table, with actions(view, update, delete), if it actions enabled.

## Code sample

```
{% include molecule('cart-table', 'MultiCartWidget') with {
    data: {
        carts: carts,
        widgets: widgets,
        actions: {
            view: true,
            update: false,
            set_default: true,
            delete: true
        }
    }
} only %}
```
