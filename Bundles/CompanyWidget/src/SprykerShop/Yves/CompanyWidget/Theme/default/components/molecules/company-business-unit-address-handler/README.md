# company-business-unit-address-handler (molecule)

Implements logic to fill form with customer or business address data.

## Code sample

```
{% include molecule('company-business-unit-address-handler', 'CompanyWidget') with {
    attributes: {
        'form-selector': '.js-form-handler__' ~ data.formType,
        'trigger-selector': '.js-from-add-new-address__' ~ data.formType,
        'data-selector': '.js-from-select-address__' ~ data.formType,
        'ignore-selector': '.js-form-clear__ignore-element',
        'customer-address-id-selector': '.js-form-customer-id__' ~ data.formType,
        'default-address-selector': '.js-form-default-address__' ~ data.formType,
        'custom-address-trigger': '.js-from-clear__'~ data.formType ~'-toggler',
        'addresses': data.addresses
    }
} only %}
```
