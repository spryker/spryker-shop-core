# product-options (molecule)

Shows the list of product options in "value: price" format.

## Code sample

```
{% include molecule('product-options', 'ProductOptionWidget') with {
    data: {
        options: data.cartItem.productOptions
    }
} only %}
```
