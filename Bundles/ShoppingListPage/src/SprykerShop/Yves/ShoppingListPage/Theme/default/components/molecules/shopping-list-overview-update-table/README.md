Displays a list of shopping lists as table which includes name, owner, date of creation, access, number of items and actions links.

## Code sample 

```
{% include molecule('shopping-list-overview-update-table', 'ShoppingListPage') with {
    data: {
        shoppingListItems: data.shoppingList.items,
        idShoppingList: data.idShoppingList,
        shoppingList: data.shoppingList,
        shoppingListItemProducts: data.shoppingListItemProducts,
        form: data.form
    }
} only %}
```
