# spelling-suggestion (molecule)

Displays a suggested query if a value was entered incorrectly in the search field.

## Code sample

```
{% include molecule('spelling-suggestion', 'CatalogPage') with {
     data: {
          suggestion: 'suggestion'
     }
 } only %}
```
