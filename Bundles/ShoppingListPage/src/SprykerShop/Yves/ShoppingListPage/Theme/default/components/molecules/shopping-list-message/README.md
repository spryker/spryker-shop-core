# shopping-list-message (molecule)

Displays a warning message if you are trying to delete a shopping list.

## Code sample 

```
{% include molecule('shopping-list-message', 'ShoppingListPage') with {
    data: {
        title: data.title,
        backUrl: data.backUrl,
        idShoppingList: data.shoppingList.idShoppingList,
        sharedCompanyUsers: data.sharedCompanyUsers,
        sharedCompanyBusinessUnits: data.sharedCompanyBusinessUnits
    }
} only %}
```
