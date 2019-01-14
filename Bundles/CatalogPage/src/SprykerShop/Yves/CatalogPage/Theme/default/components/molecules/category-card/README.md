# category-card (molecule)

Used for a short display of article as a grid item, which may contain a title, an image, and a button.

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
