# shopping-list-product-option-list (molecule)

Displays a list of product options as drop-downs on shopping list edit page.

## Code sample

```
{% include molecule('shopping-list-product-option-list', 'ProductOptionWidget') with {
    data: {
        options: data.options
    }
} only %}
```
