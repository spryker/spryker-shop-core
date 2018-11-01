# share-list (molecule)

Displays a list of business units that you can share your shopping list.

## Code sample 

```
{% include molecule('share-list', 'ShoppingListPage') with {
    data: {
        shareUnits: data.shareUnits,
        shareForm: data.shareForm
    }
} only %}
```
