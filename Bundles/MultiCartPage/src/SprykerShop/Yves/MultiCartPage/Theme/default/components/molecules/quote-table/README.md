# quote-table (molecule)

Displays table with list of shopping carts with short description.

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
