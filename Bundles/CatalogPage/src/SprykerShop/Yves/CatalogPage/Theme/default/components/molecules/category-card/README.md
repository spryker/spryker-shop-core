# category-card (molecule)

A card component specifically designed to display a single product category in a grid view. It may have title, image and link button.

## Code sample

```
{% include molecule('category-card', 'CatalogPage') with {
    data: {
        id: data.id_category,
        name: data.name,
        url: data.url
    }
} only %}
```
