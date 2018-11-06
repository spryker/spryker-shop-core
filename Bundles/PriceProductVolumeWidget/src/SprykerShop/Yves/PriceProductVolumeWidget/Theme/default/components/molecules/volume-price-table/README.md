# volume-price-table (molecule)

Displays a product price range table.

## Code sample 

```
{% include molecule('volume-price-table', 'PriceProductVolumeWidget') with {
    data: {
        volumePrices: data.volumePrices
    }
} only %}
```
