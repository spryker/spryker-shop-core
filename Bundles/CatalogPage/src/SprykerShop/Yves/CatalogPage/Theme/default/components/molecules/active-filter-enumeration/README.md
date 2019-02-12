Displays a list of all the filters selected from a category in the form of links.

## Code sample

```
{% include molecule('active-filter-enumeration', 'CatalogPage') with {
    filter: filter,
    isMultiSelect: false
} only %}
```
