# variant (molecule)

If a product has many variants, displays it as a list in which you can choose one of the options.

## Code sample 

```
{% include molecule('variant', 'DiscountPromotionWidget') with {
    data: {
        name: name,
        formName: 'attributes[' ~ data.idProductAbstract ~ '][' ~ name ~ ']',
        values: values,
        selectedValue: selectedValue,
        label: ('product.attribute.' ~ name) | trans,
        isAvailable: isAvailable
    }
} only %}
```
