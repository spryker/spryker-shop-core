Displays customer orders as a table, which includes order id, order date, total, and action links (view order, reorder).

## Code sample

```
{% include molecule('order-table', 'CustomerPage') with {
    data: {
        orders: orders
    }
} only %}
```
