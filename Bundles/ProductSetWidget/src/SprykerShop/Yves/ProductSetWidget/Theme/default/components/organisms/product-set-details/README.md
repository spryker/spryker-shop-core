# product-set-details (organism)

Displays a CMS section, which includes text messages, simple carousel with images, list of products, which you can add to your shopping cart.

## Code sample

```
{% include organism('product-set-details', 'ProductSetWidget') ignore missing with {
    data: {
        products: data.views,
        name: data.set.name,
        description: data.set.description,
        images: data.set.imageSets | default([])
    }
} only %}
```
