# wishlist-detail-table (molecule)

Displays a list of product in wishlist as table which includes product details, price, availability, and action links.

## Code sample 

```
{% include molecule('wishlist-detail-table', 'WishlistPage') with {
    data: {
        wishlistItems: data.wishlistItems,
        wishlistName: data.wishlistName
    }
} only %}
```
