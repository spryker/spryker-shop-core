# alternative-products-table (molecule)

Displays a table with alternative products limited by maxShownItems option.

## Code sample

```
{% include molecule('alternative-products-table', 'ProductAlternativeWidget') with {
    data: {
        item: data.item,
        shoppingList: data.shoppingList,
        products: data.products
    }
} only %}
```
