Displays a form to add a new wishlist which includes a text field, and a submit button.

## Code sample 

```
{% include molecule('shop-list-item', 'ShoppingListWidget') with {
    data: {
        shoppingList: shoppingList
    }
} only %}
```
