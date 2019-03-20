Displays a product image or a text message that the image is not available if there is no image source.

## Code sample

```
{% include molecule('cart-image', 'CartPage') with {
    data: {
        name: 'name',
        image: null
    }
} only %}
```
