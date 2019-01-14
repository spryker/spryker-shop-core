# category-list-item (molecule)

Used for a short display of article as a list item, which may contain a title, an image, and a button.

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
