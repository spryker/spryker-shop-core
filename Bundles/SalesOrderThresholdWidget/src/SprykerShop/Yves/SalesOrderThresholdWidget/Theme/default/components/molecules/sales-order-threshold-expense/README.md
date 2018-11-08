# sales-order-threshold-expense (molecule)

Displays a threshold expense name and price if order sum less than acceptable and the soft threshold is present.

## Code sample

```
{% include molecule('sales-order-threshold-expense', 'SalesOrderThresholdWidget') with {
    data: {'expense': expense}
} only %}
```
