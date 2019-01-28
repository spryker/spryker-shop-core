Displays a form with two fields (drop-down list and button); clicking the button next to a product, adds the product to wishlist.

## Code sample 

```
{% include molecule('wishlist-selector', 'WishlistWidget') with {
    data: {
        sku: data.sku,
        idProductConcrete: data.idProductConcrete
    }
} only %}
```
