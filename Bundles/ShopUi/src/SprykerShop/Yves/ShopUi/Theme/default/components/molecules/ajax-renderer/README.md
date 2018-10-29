# ajax-renderer (molecule)

Renders HTML received from the related ajax-provider component into a specific DOM-element.

## Code sample

```
{% include molecule('ajax-renderer') with {
    attributes: {
        'provider-selector': 'provider-selector',
        'render-if-response-is-empty': false,
        'target-selector': 'target-selector'
    }
} only %}
```
