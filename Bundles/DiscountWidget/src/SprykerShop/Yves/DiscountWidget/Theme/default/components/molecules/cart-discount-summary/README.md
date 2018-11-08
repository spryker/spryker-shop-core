# cart-discount-summary (molecule)

Displays a list of vouchers with remove link and discount rules.

## Code sample

```
{% include molecule('cart-discount-summary', 'DiscountWidget') with {
    data: {
        voucherDiscounts: data.cart.voucherDiscounts,
        ruleDiscounts: data.cart.cartRuleDiscounts,
        discountTotal: data.cart.totals.discounttotal
    }
} only %}
```
