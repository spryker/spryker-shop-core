# variant (molecule)

Shows dropdown with product variantes as options in it. Reloads page when option is changed. 

## Code sample 

```
{% include molecule('variant', 'ProductDetailPage') with {
    data: {
        name: 'name',
        formName: 'formName',
        values: values,
        selectedValue: 'selectedValue',
        label: 'label',
        isAvailable: true
    }
} only %}   
```
