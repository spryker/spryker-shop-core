Displays threshold name and price if an order total price does not reach it while the soft threshold option is enabled.

## Code sample

```
{% include molecule('sales-order-threshold-expense', 'SalesOrderThresholdWidget') with {
    data: {'expense': expense}
} only %}
```
