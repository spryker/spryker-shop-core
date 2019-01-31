Shows a drop-down menu with product variants as options in it. Reloads page when an option is changed.

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
