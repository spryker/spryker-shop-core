# summary-product-packaging-unit-default (molecule)

Displays a product total price without product quantity on checkout summary step.

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
