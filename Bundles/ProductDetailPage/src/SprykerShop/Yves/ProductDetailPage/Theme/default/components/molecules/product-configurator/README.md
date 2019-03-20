Shows a block with the most important product information and provides ability to configure product using the variant-configurator component. Also has the quantity drop-down menu and the Add to Cart button.

## Code sample 

```
{% include molecule('product-configurator', 'ProductDetailPage') with {
    data: {
        product: data.product
    }
} only %}
```
