Creates a custom element that adds/removes a class to/from the DOM-elements specified by the selector on change.

## Code sample

```
{% include molecule('address-form-toggler', 'CustomerPage') with {
    attributes: {
        'trigger': data.forms.shipping.id_customer_address,
        'trigger-target': '.js-address__shipping'
    }
} only %}
```
