Displays quote requests as a table with action links (view, cancel).

## Code sample

```
{% include molecule('quote-request-table', 'AgentQuoteRequestPage') with {
    data: {
        quoteRequests: data.quoteRequests
    }
} only %}
```
