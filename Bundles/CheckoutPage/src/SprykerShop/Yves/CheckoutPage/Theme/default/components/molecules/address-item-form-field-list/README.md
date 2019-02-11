Creates a split delivery form which includes product card, address form and checkbox for saving new address into customer account.

## Code sample

```
{% include molecule('address-item-form-field-list', 'CheckoutPage') with {
    data: {
        items: data.form,
        isAddressSavingSkipped: embed.isAddressSavingSkipped
    }
} only %}
```
