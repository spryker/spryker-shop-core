# customer-list (molecule)

Renders a list of customers with first name, last name and email.

## Code sample

```
{% include molecule('customer-list', 'AgentWidget') with {
    data: {
        customers: customers
    }
} only%}
```
