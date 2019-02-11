Creates a list of split delivery forms and link to manage customer adresses.

## Code sample

```
{% include molecule('address-item-form', 'CheckoutPage') with {
    data: {
        form: embed.forms.items,
        isAddressSavingSkipped: data.form.isAddressSavingSkipped
    }
} only %}
```
