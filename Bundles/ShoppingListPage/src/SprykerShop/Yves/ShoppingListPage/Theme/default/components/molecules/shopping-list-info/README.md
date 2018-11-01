# shopping-list-info (molecule)

Displays a short info about owner, access, and number of users or business units whom access was shared.

## Code sample 

```
{% include molecule('shopping-list-info', 'ShoppingListPage') with {
    data: {
        shoppingList: data.shoppingList
    }
} only %}
```
