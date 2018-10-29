# breadcrumb-step (molecule)

Simple list item that displays a hierarchical structure of breadcrumbs.

## Code sample

```
{% embed molecule('breadcrumb-step') with {
    data: {
        itemProp: null,
        label: 'label',
        url: 'url',
        withChevron: false
    }
} only %}
```
