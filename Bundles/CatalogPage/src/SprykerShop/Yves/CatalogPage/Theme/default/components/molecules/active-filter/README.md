# active-filter (molecule)

Displays a selected filter as link, filter disables after pressing link.

## Code sample

```
{% include molecule('active-filter', 'CatalogPage') with {
    attributes: attributes,
    data: {
        filter: filter,
        value: value
    }
} only %}
```
