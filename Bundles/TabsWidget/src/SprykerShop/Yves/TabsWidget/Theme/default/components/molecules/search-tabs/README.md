Displays a horizontal tab which includes search results (CMS content, list of products, etc.).

## Code sample

```
{% include molecule('search-tabs', 'TabsWidget') with {
    data: {
        tabs: data.tabs,
        searchString: data.searchString,
        requestParams: data.requestParams
    }
} only %}
```
