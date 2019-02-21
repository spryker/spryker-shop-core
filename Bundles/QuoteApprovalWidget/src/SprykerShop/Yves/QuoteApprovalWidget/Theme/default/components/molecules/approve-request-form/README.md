Displays a form for quote approval request.

## Code sample

```
{% include molecule('approve-request-form', 'QuoteApprovalWidget') with {
    data: {
        quoteApprovalRequestForm: data.quoteApprovalRequestForm,
        quoteApprovalRequestFormOptions: data.quoteApprovalRequestFormOptions
    }
} only %}
```
