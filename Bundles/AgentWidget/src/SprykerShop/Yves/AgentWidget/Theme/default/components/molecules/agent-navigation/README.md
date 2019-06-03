Displays the agent navigation links sub-page.

## Code sample

```
{% include molecule('agent-navigation', 'AgentWidget') with {
    data: {
        agent: data.agent,
    }
} only %}
```
