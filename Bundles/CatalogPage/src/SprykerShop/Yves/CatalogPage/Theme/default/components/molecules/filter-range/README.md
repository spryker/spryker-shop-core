# filter-range (molecule)

Allows you to filter manually by range with the minimum and maximum values.

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
