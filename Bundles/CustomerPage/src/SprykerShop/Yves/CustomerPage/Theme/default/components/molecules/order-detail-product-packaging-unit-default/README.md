# order-detail-product-packaging-unit-default (molecule)

Displays a list of order details as table which includes product sku, name, price, quantity, and item total.

## Code sample 

```
{% include molecule('order-detail-product-packaging-unit-default', 'CustomerPage') with {
    data: {
        concreteItem: item,
        currencyIsoCode: data.currencyIsoCode,
        concreteItemDefault: concreteItem
    }
} only %}
```
