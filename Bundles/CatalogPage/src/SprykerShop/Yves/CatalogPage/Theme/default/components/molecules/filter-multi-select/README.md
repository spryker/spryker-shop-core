Displays a list of all the filters from a category, in which multiple filters can be selected.

## Code sample

```
{% include molecule('filter-multi-select', 'CatalogPage') with {
     data: {
         isMultiSelect: true
     }
 } only %}
```
