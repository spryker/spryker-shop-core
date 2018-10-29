# order-table (molecule)

Displays customer account order table.

## Code sample

```
{% include molecule('order-table', 'CustomerPage') with {
    data: {
        orders: orders
    }
} only %}
```
