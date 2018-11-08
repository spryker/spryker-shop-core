# json-scheme (atom)

Renders a json data as an attribute into html tag.

## Code sample 

```
{% include atom('json-scheme', 'ProductMeasurementUnitWidget') with {
    data: {
        name: config.name,
        json: data.json
    }
} only %}
```
