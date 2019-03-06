Displays a table with a list of shopping carts and their descriptions.

## Code sample

```
{% include molecule('quote-table', 'MultiCartPage') with {
    data: {
        quotes: data.quotes,
        actions: {
            update: true,
            delete: true,
            duplicate: true
        },
        isQuoteDeletable: data.isQuoteDeletable
    }
} only %}
```
