# active-filter (molecule)

Displays a selected filter as a link, filter disables after clicking on link.

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
