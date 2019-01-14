# category-list-item (molecule)

A card-item component specifically designed to display a single product category in a list view. It may have title, image and link button.

## Code sample

```
{% include molecule('category-list-item', 'CatalogPage') with {
    data: {
        id: data.id_category,
        name: data.name,
        url: data.url
    }
} only %}
```
