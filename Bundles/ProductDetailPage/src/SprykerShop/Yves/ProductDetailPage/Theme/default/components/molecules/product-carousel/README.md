# product-carousel (molecule)

Creates a carousel with product images using the simple-carousel component; also shows the product labels.

## Code sample 

```
{% include molecule('product-carousel', 'ProductDetailPage') with {
    data: {
        product: data.product
    }
} only %}
```
