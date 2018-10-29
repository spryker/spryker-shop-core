# active-filter-price-range (molecule)

Displays a selected filter, which shows price range.

## Code sample

```
{% include molecule('active-filter-price-range', 'CatalogPage') with {
     data: {
         filter: filter,
         min: min,
         activeMin: activeMin,
         max: max,
         activeMax: activeMax
     }
 } only %}
```
