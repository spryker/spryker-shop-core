Displays a list of product options as a text in the shopping list page.

## Code sample

```
{% include molecule('shopping-list-item-product-option-display', 'ProductOptionWidget') with {
    data: {
        options: item.productOptions
    }
} only %}
```
