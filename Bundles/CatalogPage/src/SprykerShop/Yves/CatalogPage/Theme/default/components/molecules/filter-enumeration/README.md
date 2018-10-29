# filter-enumeration (molecule)

Displays a list of all filters from one category, in which you can select multiple filters.

## Code sample

```
{% include molecule('filter-enumeration', 'CatalogPage') with {
     data: {
        filter: filter,
        isMultiSelect: isMultiSelect
     }
 } only %}
```
