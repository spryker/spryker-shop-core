# form-data-injector (molecule)

Copies a form data to hidden form feilds on submit.

## Code sample 

```
{% include molecule('form-data-injector', 'ShoppingListWidget') with {
    attributes: {
        'destination-form-selector': '.js-shopping-list__form',
        'fields-selector': '.js-product-configurator__form'
    }
} only %}
```
