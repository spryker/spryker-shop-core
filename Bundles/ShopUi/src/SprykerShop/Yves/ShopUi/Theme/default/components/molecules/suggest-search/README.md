# suggest-search (molecule)

Implements search suggestions functionality for search input, creates empty container for search suggestion results, uses ajax-provider and ajax-loader.

## Code sample

```
{% include molecule('suggest-search') with {
    attributes: {
        'suggestion-url': suggestion-url,
        'base-suggest-url': 'base-suggest-url,
        'input-selector': 'input-selector',
        'debounce-delay': debounce-delay,
        'throttle-delay': throttle-delay,
        'letters-trashold': letters-trashold
    }
} only %}
```
