Displays a short description of an item card with image, name, and price in the search drop-down menu.

## Code sample

```
{% include molecule('suggestion-product', 'CatalogPage') with {
    data: {
        product: product,
        url: url,
        image: null,
        alt: 'alt',
        title: 'title',
        price: 'price',
        originalPrice: 'originalPrice'
    }
} only %}
```
