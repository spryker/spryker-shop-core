Displays information about customers order and provide reorder products functionality with selected products

## Code sample

```
{% molecule('customer-reorder-form', 'CustomerReorderWidget') with {
    data: {
        idSalesOrder: data.order.idSalesOrder
    }
} only %}
```
