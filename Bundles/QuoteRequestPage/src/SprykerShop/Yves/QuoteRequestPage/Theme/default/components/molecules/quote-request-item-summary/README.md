Displays product summary information: price, price for different options, total and subtotal.

## Code sample

```
{% include molecule('quote-request-item-summary', 'QuoteRequestPage') with {
    data: {
        priceMode: 'priceMode',
        unitPrice: 'unitPrice',
        subtotalPrice: 'subtotalPrice',
        cartItem: cartItem,
        currency: currency
    }
} only %}
```
