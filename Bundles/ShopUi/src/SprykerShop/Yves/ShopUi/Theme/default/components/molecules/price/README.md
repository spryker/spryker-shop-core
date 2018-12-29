# price (molecule)

Displays the product price with an icon and shows the original price in case of a discount.

## Code sample

```
{% include molecule('price') with {
    data: {
        amount: 'amount',
        originalAmount: 'originalAmount'
    }
} only %}
```
