# flash-message (molecule)

Displays a simple notification that informs users about important events that may have happened.

## Code sample

```
{% include molecule('display-address') with {
    data: {
        action: 'action',
        title: 'title',
        text: 'text',
        icon: null
    }
} only %}
```
