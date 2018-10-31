# filter-rating (molecule)

Allows you to filter by rating. The values are normalized within boundaries. The maximum value can be set (to max), the minimum is always 0.

## Code sample

```
{% include molecule('filter-rating', 'CatalogPage') with {
     data: {
         filter: filter
     }
 } only %}
```
