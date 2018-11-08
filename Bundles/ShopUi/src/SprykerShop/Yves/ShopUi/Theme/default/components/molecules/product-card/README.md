# product-card (molecule)

Creates a block with detailed product information: product labels, list of product group icons, product name, product rating and product price. Designed to be displayed mostly in grid layouts.

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
