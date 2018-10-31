# order-summary (molecule)

Displays customer list order summary.

## Code sample

```
{% include molecule('order-summary', 'CustomerPage') with {
    data: {
        order: order
    }
} only %}
```
