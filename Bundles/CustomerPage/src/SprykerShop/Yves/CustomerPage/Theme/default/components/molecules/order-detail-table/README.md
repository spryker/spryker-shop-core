Displays customer order details as a table, which includes product SKU, name, price, quantity, and item total.

## Code sample

```
{% include molecule('order-detail-table', 'CustomerPage') with {
    data: {
        items: items,
        currencyIsoCode: 'currencyIsoCode'
    }
} only %}
```
