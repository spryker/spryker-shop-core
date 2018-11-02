# add-wishlist (molecule)

Displays a form to add a new wishlist which includes text field, and submit button. 

## Code sample 

```
{% include molecule('shop-list-item', 'ShoppingListWidget') with {
    data: {
        shoppingList: shoppingList
    }
} only %}
```
