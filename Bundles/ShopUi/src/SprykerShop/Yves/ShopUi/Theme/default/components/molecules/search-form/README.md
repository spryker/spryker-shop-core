Creates a search input field, a submit button to trigger the search; includes the suggest-search molecule which adds search suggestions functionality to the search input.

## Code sample

```
{% include molecule('search-form') with {
    attributes: {
        'data-search-id': 'data-search-id'
    }
} only %}
```
