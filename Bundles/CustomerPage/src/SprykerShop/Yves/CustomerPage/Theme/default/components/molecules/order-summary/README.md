# order-summary (molecule)

Displays a summary of customer order as a subtotal, shipment, discount, and grand total.

## Code sample

```
{% include molecule('order-summary', 'CustomerPage') with {
    data: {
        order: order
    }
} only %}
```
