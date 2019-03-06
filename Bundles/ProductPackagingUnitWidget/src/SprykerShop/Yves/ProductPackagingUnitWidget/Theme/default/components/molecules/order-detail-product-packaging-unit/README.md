Displays a list of order details as table which includes product SKU, name, price, quantity, and item total.

## Code sample 

```
{% include molecule('order-detail-product-packaging-unit', 'ProductPackagingUnitWidget') with {
    data: {
        concreteItem: item,
        currencyIsoCode: data.currencyIsoCode,
        concreteItemDefault: concreteItem
    }
} only %}
```
