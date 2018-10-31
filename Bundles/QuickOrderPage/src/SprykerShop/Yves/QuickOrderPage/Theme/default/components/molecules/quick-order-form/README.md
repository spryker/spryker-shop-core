# quick-order-form (molecule)

Displays a form with several rows in which includes SKU and quontity fields. Orders with multiple SKUs can be entered manually, also user can add/remove rows.

## Code sample 

```
{% include molecule('quick-order-form', 'QuickOrderPage') with {
    data: {
        form: data.forms.items
    }
} only %}
```
