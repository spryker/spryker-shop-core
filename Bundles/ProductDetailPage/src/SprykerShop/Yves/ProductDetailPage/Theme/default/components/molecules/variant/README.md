# variant (molecule)

Shows a drop-down with product variants as options in it. Reloads a page when the option is changed.

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
