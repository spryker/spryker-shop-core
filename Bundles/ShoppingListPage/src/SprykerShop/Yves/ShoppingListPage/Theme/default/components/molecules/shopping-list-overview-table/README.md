Displays a list of shopping lists as table which includes name, owner, date of creation, access, number of items and actions links.

## Code sample 

```
{% include molecule('shopping-list-overview-table', 'ShoppingListPage') with {
    data: {
        shoppingLists: data.shoppingListCollection.shoppingLists,
        enableTableForm: true,
        shoppingListResponse: data.shoppingListResponse
    }
} only %}
```
