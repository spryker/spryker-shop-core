# json-scheme (atom)

Renders json data as an attribute into html tag.

## Code sample 

```
{% include atom('json-scheme', 'ProductPackagingUnitWidget') with {
    data: {
        name: config.name,
        json: data.json
    }
} only %}
```
