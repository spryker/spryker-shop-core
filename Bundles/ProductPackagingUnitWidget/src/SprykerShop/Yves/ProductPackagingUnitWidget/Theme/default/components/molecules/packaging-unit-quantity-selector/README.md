# packaging-unit-quantity-selector (molecule)

Shows html tag element, which includes json data as an html attribute.

## Code sample 

```
{% include molecule('packaging-unit-quantity-selector', 'ProductPackagingUnitWidget') with {
    data: {
        json: data.jsonScheme
    }
} only %}
```
