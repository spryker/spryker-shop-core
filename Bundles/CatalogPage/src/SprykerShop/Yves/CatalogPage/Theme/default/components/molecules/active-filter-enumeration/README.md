# active-filter-enumeration (molecule)

Displays a list of all the filters selected from one category in form of links.

## Code sample

```
{% include molecule('active-filter-enumeration', 'CatalogPage') with {
    filter: filter,
    isMultiSelect: false
} only %}
```
