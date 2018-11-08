# search-form (molecule)

Creates search from with input field and submit button. Also contain suggest-search molecule which add search suggestions functionality to search input.

## Code sample

```
{% include molecule('search-form') with {
    attributes: {
        'data-search-id': 'data-search-id'
    }
} only %}
```
