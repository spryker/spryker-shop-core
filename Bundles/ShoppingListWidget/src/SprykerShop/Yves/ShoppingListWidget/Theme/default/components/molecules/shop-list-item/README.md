At top navigation menu, displays a drop-down list with shopping lists which includes shopping list name, customer name, number of products and access rights.

## Code sample 

```
{% include molecule('shop-list-item', 'ShoppingListWidget') with {
    data: {
        shoppingList: shoppingList
    }
} only %}
```
