Displays a list of all the filters from a category, in which a single filter can be selected.

## Code sample

```
{% include molecule('filter-single-select', 'CatalogPage') with {
     data: {
         isMultiSelect: false
     }
 } only %}
```
