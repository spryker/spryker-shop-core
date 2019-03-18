Displays a form row which includes autocomplete products search, measuring unit, quantity, price and remove button.

## Code sample 

```
{% include molecule('quick-order-row', 'QuickOrderPage') with {
    data: {
        index: loop.index0,
        quantityField: row.quantity,
        skuField: row.sku,
        product: product,
        autocomplete: {
            skuFieldName: row.vars.full_name,
            skuFieldValue: product is null ? null : product.sku,
            searchFieldValue: product is null ? null : product.localizedAttributes[0].name ~ ' (' ~ product.sku ~ ')'
        }
    }
} only %}
```
