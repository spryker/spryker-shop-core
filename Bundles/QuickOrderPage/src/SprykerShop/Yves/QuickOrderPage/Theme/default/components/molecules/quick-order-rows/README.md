# quick-order-rows (molecule)

Displays a list of form rows in which includes autocomplete products searching, measuring unit, quantity, price, and remove button. 

## Code sample 

```
{% include molecule('quick-order-rows', 'QuickOrderPage') with {
    data: {
        rows: data.form.items,
        products: data.products,
        additionalColumns: data.additionalColumns,
        prices: data.prices
    }
} only %}
```
