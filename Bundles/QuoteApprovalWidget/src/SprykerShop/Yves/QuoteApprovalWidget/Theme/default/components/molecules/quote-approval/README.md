Displays information about current quote approval status relevant for approver.

## Code sample

```
{% include molecule('quote-approval', 'QuoteApprovalWidget') with {
    data: {
        customerName: customerName,
        updateDate: updateDate,
        quoteTransfer: data.quoteTransfer
    }
} only %}
```
