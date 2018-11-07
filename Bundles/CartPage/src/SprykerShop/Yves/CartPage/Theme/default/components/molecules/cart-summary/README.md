# cart-summary (molecule)

Displays information about quantity of products in the shopping cart, cart subtotal, total, and grand total, as well as information about discounts and link to the checkout.

## Code sample

```
{% include molecule('cart-summary', 'CartPage') with {
    data: {
        cart: cart,
        isQuoteValid: isQuoteValid
    }
} only %}
```
