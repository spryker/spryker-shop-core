Displays a drop-down menu at the top navigation bar with the list of quote requests.

## Code sample

```
{% include molecule('quote-request-detail', 'AgentQuoteRequestWidget') with {
    data: {
        quoteRequest: quoteRequest
    }
} only %}
```
