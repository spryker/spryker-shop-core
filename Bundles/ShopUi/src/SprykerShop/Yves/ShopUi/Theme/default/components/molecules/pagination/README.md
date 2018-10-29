# pagination (molecule)

Creates a customizable pagination.

## Code sample

```
{% include molecule('pagination') with {
    parameters: parameters,
    currentPage: currentPage,
    paginationPath: 'paginationPath',
    showAlwaysFirstAndLast: false,
    maxPage: maxPage,
    extremePagesLimit: extremePagesLimit,
    nearbyPagesLimit: nearbyPagesLimit,
    anchor: 'anchor'
} only %}
```
