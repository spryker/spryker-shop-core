# availability-product (molecule)

Shows alert text message if product is not available.

## Code sample

```
{% include molecule('availability-product', 'AvailabilityWidget') ignore missing with {
    data: {
        idProductConcrete: data.product.idProductConcrete,
        isAvailable: data.product.available
    }
} only %}
```
