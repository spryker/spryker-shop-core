# json-scheme (atom)

Renders a json data as attribute into html tag.

## Code sample 

```
{% include atom('json-scheme', 'ProductPackagingUnitWidget') with {
    data: {
        name: config.name,
        json: data.json
    }
} only %}
```
