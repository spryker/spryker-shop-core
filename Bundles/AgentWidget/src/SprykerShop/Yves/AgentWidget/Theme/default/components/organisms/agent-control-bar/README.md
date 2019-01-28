Displays a grid with the autocomplete form for user selection as well as a list of users with the logout link.

## Code sample

```
{% include organism('agent-control-bar', 'AgentWidget') with {
    data: {
        agent: agent,
        customer: customer
    }
} only %}
```
