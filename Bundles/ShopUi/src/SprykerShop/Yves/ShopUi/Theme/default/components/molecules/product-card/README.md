Creates a block with product information: product labels, list of product group icons, product name, product rating and product price.

## Code sample

```
{% include molecule('product-card') with {
    data: {
        name: 'name',
        abstractId: abstractId,
        url: 'url',
        imageUrl: 'imageUrl',
        price: 'price',
        originalPrice: 'originalPrice'
    }
} only %}
```
