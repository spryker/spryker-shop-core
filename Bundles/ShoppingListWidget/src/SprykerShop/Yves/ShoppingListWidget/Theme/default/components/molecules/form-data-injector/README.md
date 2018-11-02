# form-data-injector (molecule)

Displays a link which on click add shopping cart to shopping list.

## Code sample 

```
{% include molecule('form-data-injector', 'ShoppingListWidget') with {
    attributes: {
        'destination-form-selector': '.js-shopping-list__form',
        'fields-selector': '.js-product-configurator__form'
    }
} only %}
```
