Displays a list of product options as drop-down menu options on the shopping list edit page.

## Code sample

```
{% include molecule('shopping-list-product-option-list', 'ProductOptionWidget') with {
    data: {
        options: data.options
    }
} only %}
```
