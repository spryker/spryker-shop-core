# order-detail-table (molecule)

Displays customer order detail table.

## Code sample

```
{% include molecule('order-detail-table', 'CustomerPage') with {
    data: {
        items: items,
        currencyIsoCode: 'currencyIsoCode'
    }
} only %}
```
