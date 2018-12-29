# shopping-list-note-toggler (molecule)

Displays a simple link which toggles a text area field when clicked on.

## Code sample 

```
{% include molecule('shopping-list-note-toggler', 'ShoppingListNoteWidget') with {
    data: {
        shoppingListItemNoteForm: form
    }
} only %}
```
