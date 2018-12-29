# filter-rating (molecule)

Enables filtering by rating. The values are normalized within boundaries. The maximum value can be set (to max) while the minimum one is always 0.

## Code sample

```
{% include molecule('filter-rating', 'CatalogPage') with {
     data: {
         filter: filter
     }
 } only %}
```
