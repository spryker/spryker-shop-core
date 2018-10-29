# active-filter-enumeration (molecule)

Displays a list of all selected filters from one category as links.

## Code sample

```
{% include molecule('active-filter-enumeration', 'CatalogPage') with {
    filter: filter,
    isMultiSelect: false
} only %}
```
