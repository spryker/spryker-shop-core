# quick-order-form (molecule)

Displays a form with several rows in which includes autocomplete products searching, measuring unit, quantity, and price. Also user can add/remove rows, add products to the cart or directly create new order.

## Code sample 

```
{% include molecule('quick-order-form', 'QuickOrderPage') with {
    data: {
        form: data.forms.quickOrderForm,
        products: data.products,
        prices: data.prices,
        additionalColumns: data.additionalColumns
    }
} only %}
```
