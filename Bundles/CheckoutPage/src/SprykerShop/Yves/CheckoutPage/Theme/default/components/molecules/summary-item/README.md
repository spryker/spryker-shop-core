# summary-item (molecule)



## Code sample 

```
{% include molecule('summary-item', 'CheckoutPage') with {
    data: {
        name: item.name,
        quantity: item.quantity,
        price: item.sumPrice | money,
        options: item.productOptions,
        bundleItems: item.bundleItems,
        quantitySalesUnit: item.quantitySalesUnit,
        amountSalesUnit: item.amountSalesUnit,
        amount: item.amount
    }
} only %}
```
