# quick-order-row-partial (molecule)

Displays a form row which includes autocomplete products search, measuring unit, quantity, price and remove button.

## Code sample 

```
{% include molecule('quick-order-row-partial', 'QuickOrderPage') with {
    data: {
        quantityField: data.form ? data.form.quantity : null,
        customQuantityFieldName: 'quick_order_form[items][' ~ data.index ~ '][quantity]',
        price: data.price,
        additionalColumns: data.additionalColumns,
        product: data.product,
        isQuantityAdjusted: data.isQuantityAdjusted
    }
} only %}
```
