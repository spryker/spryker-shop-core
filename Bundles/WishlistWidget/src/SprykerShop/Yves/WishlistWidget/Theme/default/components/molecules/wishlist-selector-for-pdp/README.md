# wishlist-selector-for-pdp (molecule)

Displays a form with two fields (drop-down and button), which on click on the button will add the product to the wishlist, selected from the drop-down.

## Code sample 

```
{% include molecule('wishlist-selector-for-pdp', 'WishlistWidget') ignore missing with {
    data: {
        sku: data.product.sku,
        idProductConcrete: data.product.idProductConcrete | default
    }
} only %}
```
