Displays a list of navigation actions which includes edit, share, print and delete links.

## Code sample 

```
{% include molecule('shopping-list-action-navigation', 'ShoppingListPage') with {
    data: {
        hasWritePermission: hasWritePermission,
        idShoppingList: shoppingList.idShoppingList,
        shoppingList: shoppingList
    }
} only %}
```
