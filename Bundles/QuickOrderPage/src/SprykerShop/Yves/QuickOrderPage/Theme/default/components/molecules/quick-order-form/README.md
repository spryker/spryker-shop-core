Displays a form with several rows which includes autocomplete product search, measuring unit, quantity and price. Also user can add/remove rows, add products to cart or directly create a new order.

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
