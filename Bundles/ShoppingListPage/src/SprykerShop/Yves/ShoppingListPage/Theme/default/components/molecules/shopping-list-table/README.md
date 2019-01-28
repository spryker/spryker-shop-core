Displays a list of products in shopping list as a table which includes product name, price, quantity, availability, and action links.

## Code sample 

```
{% include molecule('shopping-list-table', 'ShoppingListPage') with {
    data: {
        shoppingList: data.shoppingList,
        shoppingListItems: data.shoppingListItems
    }
} only %}
```
