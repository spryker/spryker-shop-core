# filter-single-select (molecule)

Displays a list of all filters from one category, in which you can select one filter.

## Code sample

```
{% include molecule('filter-single-select', 'CatalogPage') with {
     data: {
         isMultiSelect: false
     }
 } only %}
```
