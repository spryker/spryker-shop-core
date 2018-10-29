# price (molecule)

Displays the product price with icon and also shows original price in case of discount.

## Code sample

```
{% include molecule('price') with {
    data: {
        amount: 'amount',
        originalAmount: 'originalAmount'
    }
} only %}
```
