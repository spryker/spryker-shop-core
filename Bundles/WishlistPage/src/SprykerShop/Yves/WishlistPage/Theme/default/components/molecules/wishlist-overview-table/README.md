# wishlist-overview-table (molecule)

Displays a list of wishlists as table which includes wishlist name, number of items in wishlist, date of creation, and action links.

## Code sample 

```
{% include molecule('wishlist-overview-table', 'WishlistPage') with {
    data: {
        wishlists: data.wishlists,
        wishlistForm: data.wishlistForm
    }
} only %}
```
