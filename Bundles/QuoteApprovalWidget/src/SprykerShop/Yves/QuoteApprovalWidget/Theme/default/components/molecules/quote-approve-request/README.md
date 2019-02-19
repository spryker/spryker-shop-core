Displays information about current quote approve status relevant for approval request sender.

## Code sample

```
{% include molecule('quote-approve-request', 'QuoteApprovalWidget') with {
    data: {
        customer: customer,
        quoteStatus: data.quoteStatus,
        quoteApproval: quoteApproval,
        quote: data.quote
    }
} only %}
```
