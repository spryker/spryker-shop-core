# order-table (molecule)

Displays a customer orders as table, which includes order id, order date, total, and action links (view order, reorder).

## Code sample

```
{% include molecule('order-table', 'CustomerPage') with {
    data: {
        orders: orders
    }
} only %}
```
