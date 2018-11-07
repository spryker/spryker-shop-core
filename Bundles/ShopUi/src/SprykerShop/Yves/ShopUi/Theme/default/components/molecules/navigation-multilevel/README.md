# navigation-multilevel (molecule)

Creates a list of navigation items, which makes it possible to render items recursively.

## Code sample

```
{% include molecule('navigation-multilevel') with {
    data: {
        nodes: nodes,
        menuInline: false,
        menuDropdown: false
    }
} only %}
```
