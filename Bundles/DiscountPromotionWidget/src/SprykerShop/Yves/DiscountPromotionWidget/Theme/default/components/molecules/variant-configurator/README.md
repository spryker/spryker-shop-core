# variant-configurator (molecule)

Displays a list of product variants as select elements and if a variant is already selected - as a static list with reset button.

## Code sample 

```
{% include molecule('variant-configurator', 'DiscountPromotionWidget') with {
    data: {
        idProductAbstract: data.idProductAbstract,
        superAttributes: data.superAttributes,
        selectedAttributes: data.selectedAttributes,
        availableAttributes: data.availableAttributes
    }
} only %}
```
