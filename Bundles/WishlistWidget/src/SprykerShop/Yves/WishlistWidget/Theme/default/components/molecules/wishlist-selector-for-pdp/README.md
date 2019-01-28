Displays a form with two fields (drop-down list and button); clicking the button next to a product, adds the product to wishlist.

## Code sample 

```
{% include molecule('wishlist-selector-for-pdp', 'WishlistWidget') ignore missing with {
    data: {
        sku: data.product.sku,
        idProductConcrete: data.product.idProductConcrete | default
    }
} only %}
```
