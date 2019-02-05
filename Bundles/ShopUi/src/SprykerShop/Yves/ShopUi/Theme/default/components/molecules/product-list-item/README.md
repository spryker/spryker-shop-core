Creates a block with detailed product information: product labels, product image, list of product group icons, product name, product rating, product price and a link to the product.

## Code sample

```
{% include molecule('product-list-item') with {
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