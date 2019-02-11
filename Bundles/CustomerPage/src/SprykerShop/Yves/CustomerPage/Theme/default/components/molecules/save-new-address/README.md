Creates a symfony form checkbox that shows/hides if specified address need to be save/unsave.

## Code sample

```
{% include molecule('save-new-address', 'CustomerPage') with {
    data: {
        form: data.form
    },
    attributes: {
        'shipping-address-toggler-selector': '[name="' ~ embed.forms.shipping.id_customer_address.vars.full_name ~ '"]',
        'billing-address-toggler-selector': '[name="' ~ embed.forms.billing.id_customer_address.vars.full_name ~ '"]',
        'billing-same-as-shipping-toggler-selector': '[name="' ~ data.form.billingSameAsShipping.vars.full_name ~ '"]',
        'save-address-toggler-selector': '[name="' ~ data.form.isAddressSavingSkipped.vars.full_name ~ '"]'
    }
} only %}
```
