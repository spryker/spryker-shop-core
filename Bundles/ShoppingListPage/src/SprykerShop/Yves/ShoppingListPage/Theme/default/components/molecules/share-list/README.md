# share-list (molecule)

Displays a list of business units with which a shopping list can be shared.

## Code sample 

```
{% include molecule('share-list', 'ShoppingListPage') with {
    data: {
        shareUnits: data.shareUnits,
        shareForm: data.shareForm
    }
} only %}
```
