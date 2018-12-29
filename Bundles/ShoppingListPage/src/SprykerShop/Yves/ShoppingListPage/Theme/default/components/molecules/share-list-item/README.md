# share-list-item (molecule)

Displays a list item of a business unit which shows company name and drop-down menu with access variants.

## Code sample 

```
{% include molecule('share-list-item', 'ShoppingListPage') with {
    data: {
        name: user,
        shareForm: data.shareForm[key]
    }
} only %}
```
