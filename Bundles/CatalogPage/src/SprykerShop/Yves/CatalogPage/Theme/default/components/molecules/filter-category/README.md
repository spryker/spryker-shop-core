# filter-category (molecule)

Displays a list of filter categories, which enables to filter the categories in a more specific manner. A count next to each filter item informs how many products a filter item contains.

## Code sample

```
{% include molecule('filter-category', 'CatalogPage') with {
    data: {
        filter: filter,
        filterPath: filterPath,
        categories: categories,
        isEmptyCategoryFilterValueVisible: isEmptyCategoryFilterValueVisible
    }
} only %}
```
