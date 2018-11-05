# packaging-unit-cart (molecule)



## Code sample 

```
{% include molecule('packaging-unit-cart', 'ProductPackagingUnitWidget') with {
    data: {
        amount: data.cartItem.amount,
        quantity: data.cartItem.quantity,
        quantitySalesUnit: data.cartItem.quantitySalesUnit,
        quantityConversion: data.cartItem.quantitySalesUnit.conversion,
        quantityProductMeasurementUnit: data.cartItem.quantitySalesUnit.productMeasurementUnit,
        amountSalesUnit: data.cartItem.amountSalesUnit,
        amountConversion: data.cartItem.amountSalesUnit.conversion,
        amountProductMeasurementUnit: data.cartItem.amountSalesUnit.productMeasurementUnit,
        amountValue: data.cartItem.amountSalesUnit.value
    }
} only %}
```
