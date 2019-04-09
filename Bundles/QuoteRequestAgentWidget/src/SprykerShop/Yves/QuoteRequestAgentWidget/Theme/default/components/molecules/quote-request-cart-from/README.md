Displays a form at the top navigation bar with action buttons for current RfQ.

## Code sample

```
{% include molecule('quote-request-cart-from', 'QuoteRequestAgentWidget') with {
    data: {
        quoteRequestReference: quoteRequestReference,
        form: form,
    }
} only %}
```
