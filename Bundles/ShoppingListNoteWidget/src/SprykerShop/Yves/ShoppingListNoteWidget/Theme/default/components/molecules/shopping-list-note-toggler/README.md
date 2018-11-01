# shopping-list-note-toggler (molecule)

Displays a list of form rows in which includes SKU and quontity fields.

## Code sample 

```
{% include molecule('shopping-list-note-toggler', 'ShoppingListNoteWidget') with {
    data: {
        shoppingListItemNoteForm: form
    }
} only %}
```
