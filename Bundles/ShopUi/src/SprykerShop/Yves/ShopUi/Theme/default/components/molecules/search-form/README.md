# search-form (molecule)

Creates search input field, submit button to trigger search and also consist suggest-search molecule which add search suggestions functionality to search input.

## Code sample

```
{% include molecule('search-form') with {
    attributes: {
        'data-search-id': 'data-search-id'
    }
} only %}
```
