# summary-product-packaging-unit-default (molecule)

Displays a product summary price without amount on checkout summary step.

## Code sample 

```
{% include molecule('summary-product-packaging-unit-default', 'CheckoutPage') with {
    data: {
        name: data.name,
        quantity: data.quantity,
        price: data.price,
        quantitySalesUnit: data.quantitySalesUnit
    }
} only %}
```
