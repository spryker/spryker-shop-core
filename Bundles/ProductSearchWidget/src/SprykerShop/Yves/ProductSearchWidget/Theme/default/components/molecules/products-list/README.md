# products-list (molecule)

Displays a list of products in the drop-down menu of autocomplete form.

## Code sample

```
{% include molecule('products-list', 'ProductSearchWidget') with {
    products: data.products
} only %}
```
