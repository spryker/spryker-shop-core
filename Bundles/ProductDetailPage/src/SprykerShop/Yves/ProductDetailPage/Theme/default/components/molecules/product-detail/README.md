# product-detail (molecule)

Shows a list of product attributes.

## Code sample 

```
{% include molecule('product-detail', 'ProductDetailPage') with {
    class: 'box',
    data: {
        description: data.product.description,
        attributes: data.product.attributes
    }
} only %}
```
