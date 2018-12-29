# product-set-cms-content (organism)

Displays a CMS section, which includes text messages, a simple carousel with images and a list of products, which you can add to your shopping cart.

## Code sample

```
{% include organism('product-set-cms-content', 'ProductSetWidget') ignore missing with {
    data: {
        products: productViews,
        name: productSet.name,
        description: productSet.description,
        images: productSet.imageSets | default([])
    }
} only %}
```
