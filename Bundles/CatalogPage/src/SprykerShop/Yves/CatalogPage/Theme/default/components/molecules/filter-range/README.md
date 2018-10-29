# filter-range (molecule)

Allows to filter by range with minimum and maximum value manually.

## Code sample

```
{% include molecule('filter-range', 'CatalogPage') with {
     data: {
         parameter: parameter,
         min: min,
         activeMin: activeMin,
         max: max,
         activeMax: activeMax
     }
 } only %}
```
