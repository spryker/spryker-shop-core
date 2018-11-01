# shopping-list-navigation (molecule)

Displays a navigation list of shopping lists as links.

## Code sample 

```
{% include molecule('shopping-list-navigation', 'ShoppingListPage') with {
    data: {
        activeShoppingListId: data.activeShoppingListId,
        shoppingListCollection: data.shoppingListCollection
    }
} only %}
```
