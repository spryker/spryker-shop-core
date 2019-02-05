Displays a product summary price on checkout summary step.

## Code sample 

```
{% include molecule('summary-product-packaging-unit', 'ProductPackagingUnitWidget') with {
    data: {
        name: data.name,
        quantity: data.quantity,
        amount: data.amount,
        price: data.price,
        quantitySalesUnit: data.quantitySalesUnit,
        amountSalesUnit: data.amountSalesUnit
    }
} only %}
```
