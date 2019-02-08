Displays information about customer order as a date, product list, billing and shipping adresses, etc.

## Code sample

```
{% include molecule('order-detail', 'CustomerPage') with {
    data: {
        order: data.order,
        orderItems: data.orderItems
    }
} only %}
```
