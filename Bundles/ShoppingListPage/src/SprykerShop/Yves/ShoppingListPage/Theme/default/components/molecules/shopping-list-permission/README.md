# shopping-list-permission (molecule)

Displays a permission for shopping lists as 'Full access' or 'Read only'.

## Code sample 

```
{% include molecule('shopping-list-permission', 'ShoppingListPage') with {
    data: {
        hasWritePermission: hasWritePermission
    }
} only %}
```
