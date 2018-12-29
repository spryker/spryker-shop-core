# filter-range (molecule)

Enables manual filtering by the price range with minimum and maximum values.

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
