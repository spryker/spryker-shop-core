# cart-items-counter (molecule)

Displays number of items in product cart.

## Code sample

```
{% include molecule('cart-items-counter') with {
    data: {
        label: 'label',
        quantity: quantity
    }
} only %}
```
