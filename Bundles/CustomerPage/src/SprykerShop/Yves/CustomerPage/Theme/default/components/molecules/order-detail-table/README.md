# order-detail-table (molecule)

Displays a customer order details as table, which includes products sku, product name, price, quantity, and item total.

## Code sample

```
{% include molecule('order-detail-table', 'CustomerPage') with {
    data: {
        items: items,
        currencyIsoCode: 'currencyIsoCode'
    }
} only %}
```
