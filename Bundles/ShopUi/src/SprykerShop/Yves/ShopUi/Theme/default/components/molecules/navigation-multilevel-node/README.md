Creates a menu item: it can be a link, a text message or a list of links.

## Code sample

```
{% include molecule('navigation-multilevel-node') with {
    data: {
        node: node
    }
} only %}
```