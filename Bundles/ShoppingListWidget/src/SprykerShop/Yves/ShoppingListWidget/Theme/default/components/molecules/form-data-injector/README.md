# form-data-injector (molecule)


## Code sample 

```
{% include molecule('form-data-injector', 'ShoppingListWidget') with {
    attributes: {
        'destination-form-selector': '.js-shopping-list__form',
        'fields-selector': '.js-product-configurator__form'
    }
} only %}
```
