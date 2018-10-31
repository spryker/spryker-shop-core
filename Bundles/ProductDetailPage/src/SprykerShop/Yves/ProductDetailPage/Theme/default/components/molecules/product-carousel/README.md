# product-carousel (molecule)

Creates carousel with product images using simple-carousel component, also shows product labels.

## Code sample 

```
{% include molecule('product-carousel', 'ProductDetailPage') with {
    data: {
        product: data.product
    }
} only %}
```
