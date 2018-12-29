# shopping-list-permission (molecule)

Displays access rights for a shopping list (e.g. "Full access" or "Read only").

## Code sample 

```
{% include molecule('shopping-list-permission', 'ShoppingListPage') with {
    data: {
        hasWritePermission: hasWritePermission
    }
} only %}
```
