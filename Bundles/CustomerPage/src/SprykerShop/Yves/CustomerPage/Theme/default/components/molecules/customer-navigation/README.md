Displays the customer navigation links sub-page.

## Code sample

```
{% include molecule('customer-navigation', 'CustomerPage') with {
    data: {
        activePage: 'activePage',
        activeEntityId: activeEntityId
    }
} only %}
```
