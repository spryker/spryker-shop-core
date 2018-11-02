# shop-list-item (molecule)

Displays a drop-down at top navigation menu with list of shopping lists which includes shopping list name, customer name, number of products in list, and access.

## Code sample 

```
{% include molecule('shop-list-item', 'ShoppingListWidget') with {
    data: {
        shoppingList: shoppingList
    }
} only %}
```
