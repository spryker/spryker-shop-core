# product-configurator (molecule)

Shows block with most of important product information and provide ability to configure product using variant-configurator component. Also has quantity dropdown and add to cart buttton.  

## Code sample 

```
{% include molecule('product-configurator', 'ProductDetailPage') with {
    data: {
        product: data.product
    }
} only %}
```
