Shows a list of the variant drop-down menu options, optionally wrapped with the form tag.

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
