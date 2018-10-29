# variant-configurator (molecule)

Shows a list  of variant dropdowns optionally wrapped with form tag.

## Code sample 

```
{% include molecule('variant-configurator', 'ProductDetailPage') with {
    data: {
        superAttributes: data.product.attributeMap.superAttributes,
        selectedAttributes: data.product.selectedAttributes,
        availableAttributes: data.product.availableAttributes,
        useExternalForm: true
    }
} only %}
```
