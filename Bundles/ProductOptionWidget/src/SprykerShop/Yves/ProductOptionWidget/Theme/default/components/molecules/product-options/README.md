# product-options (molecule)

Shows a list of product options in a "value: price" format.

## Code sample

```
{% include molecule('product-options', 'ProductOptionWidget') with {
    data: {
        options: data.cartItem.productOptions
    }
} only %}
```
