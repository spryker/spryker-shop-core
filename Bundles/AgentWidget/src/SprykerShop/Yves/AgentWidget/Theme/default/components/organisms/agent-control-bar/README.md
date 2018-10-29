# agent-control-bar (molecule)

Displays a grid with autocomplete form for user selection as well as a list of users with logout link.

## Code sample

```
{% include organism('agent-control-bar', 'AgentWidget') with {
    data: {
        agent: agent,
        customer: customer
    }
} only %}
```
