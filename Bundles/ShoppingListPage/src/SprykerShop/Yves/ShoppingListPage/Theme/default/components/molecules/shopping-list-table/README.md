# shopping-list-table (molecule)

Displays a list of product in shopping list as table which includes product name, price, quantity, availability, and action link.

## Code sample 

```
{% include molecule('shopping-list-table', 'ShoppingListPage') with {
    data: {
        shoppingList: data.shoppingList,
        shoppingListItems: data.shoppingListItems
    }
} only %}
```
