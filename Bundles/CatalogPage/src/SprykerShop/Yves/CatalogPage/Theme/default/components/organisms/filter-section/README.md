Displays a list of all filter categories.

## Code sample

```
{% include organism('filter-section', 'CatalogPage') with {
    data: {
        facets: facets,
        filterPath: filterPath,
        categories: categories,
        isEmptyCategoryFilterValueVisible: isEmptyCategoryFilterValueVisible
    }
} only %}
```
