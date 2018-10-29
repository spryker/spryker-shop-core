# suggestion-product (molecule)

Short display of the item card with image, name and price in the search dropdown menu.

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
