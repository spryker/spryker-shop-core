# quick-order-form-field-list (molecule)

Displays a list of form rows in which includes SKU and quontity fields.

## Code sample 

```
{% include molecule('quick-order-form-field-list', 'QuickOrderPage') with {
    data: {
        items: data.form.items
    }
} only %}
```
