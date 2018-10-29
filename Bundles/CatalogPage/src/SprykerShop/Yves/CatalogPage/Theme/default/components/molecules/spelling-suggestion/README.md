# spelling-suggestion (molecule)

Displays the revised suggested query if the value was entered incorrectly in the search field.

## Code sample

```
{% include molecule('spelling-suggestion', 'CatalogPage') with {
     data: {
          suggestion: 'suggestion'
     }
 } only %}
```
