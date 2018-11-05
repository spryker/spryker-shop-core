# wishlist-selector (molecule)

Displays a form with two fields (drop-down and button), which on click on the button will add the product to the wishlist, selected from the drop-down.

## Code sample 

```
{% include molecule('wishlist-selector', 'WishlistWidget') with {
    data: {
        sku: data.sku,
        idProductConcrete: data.idProductConcrete
    }
} only %}
```
