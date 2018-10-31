# volume-price (molecule)



## Code sample 

```
{% include molecule('volume-price', 'PriceProductVolumeWidget') with {
    data: {
        amount: data.amount | money,
        originalAmount: data.originalAmount
    }
} only %}
```
