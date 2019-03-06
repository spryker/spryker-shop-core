Displays a list of products in wishlist as a table which includes product details, price, availability, and action links.

## Code sample 

```
{% include molecule('wishlist-detail-table', 'WishlistPage') with {
    data: {
        wishlistItems: data.wishlistItems,
        wishlistName: data.wishlistName
    }
} only %}
```
