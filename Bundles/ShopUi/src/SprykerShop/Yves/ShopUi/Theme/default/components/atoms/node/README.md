Creates a simple unordered list item element.

## Code sample

```
{% include atom('node') with {
    config: {
        tag: 'li'
    },
    data: {
        node: node
    }
} only %}
```
