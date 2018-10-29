# filter-multi-select (molecule)

Displays a list of all filters from one category, in which you can select multiple filters.

## Code sample

```
{% include molecule('filter-multi-select', 'CatalogPage') with {
     data: {
         isMultiSelect: true
     }
 } only %}
```
