Displays the product quantity in shopping cart, cart subtotal, total, and grand total, as well as information about discounts and a link to checkout.

## Code sample

```
{% include molecule('cart-summary', 'CartPage') with {
    data: {
        cart: cart,
        isQuoteValid: isQuoteValid
    }
} only %}
```
