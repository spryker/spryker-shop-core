# side-drawer (organism)

Displays a side navigation bar that duplicates top and main navigation bar when accessing from a mobile device.

## Code sample

```
{% include organism('side-drawer') with {
    attributes: {
        'container-selector': 'container-selector',
        'trigger-selector': 'trigger-selector'
    }
} only %}
```
