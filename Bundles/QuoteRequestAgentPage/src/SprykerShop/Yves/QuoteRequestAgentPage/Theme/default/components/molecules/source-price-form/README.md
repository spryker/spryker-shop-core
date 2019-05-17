Displays price value feild and checkbox of the use default price.

## Code sample

```
{% include molecule('source-price-form', 'QuoteRequestAgentPage') with {
    data: {
        priceFiled: data.fieldPrice,
        checkboxName: data.checkboxName,
        checkboxValue: data.checkboxValue,
        isChecked: data.isChecked,
        checkboxLabel: data.checkboxLabel
    }
} only %}
```
