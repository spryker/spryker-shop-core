# suggestion-links-list (molecule)

Displays a list of links (to product or page) in the search drop-down menu if there is a match with the entered value.

## Code sample

```
{% include molecule('suggestion-links-list', 'CatalogPage') with {
    data: {
        name: 'name',
        items: items,
        length: length,
        isTitleHiddenOnTablet: false,
        isSuggestion: true
    }
} only %}
```
