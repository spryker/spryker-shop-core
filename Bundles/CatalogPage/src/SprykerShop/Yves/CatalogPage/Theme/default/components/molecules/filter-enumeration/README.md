Displays a list of all the filters from a category, in which multiple filters can be selected.

## Code sample

```
{% include molecule('filter-enumeration', 'CatalogPage') with {
     data: {
        filter: filter,
        isMultiSelect: isMultiSelect
     }
 } only %}
```
