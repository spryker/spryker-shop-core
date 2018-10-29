# active-filter-section (organizm)

Displays a list of all selected filters from all categories as links.

## Code sample

```
{% include organism('active-filter-section', 'CatalogPage') with {
    data: {
        facets: facets
    }
} only %}
```
