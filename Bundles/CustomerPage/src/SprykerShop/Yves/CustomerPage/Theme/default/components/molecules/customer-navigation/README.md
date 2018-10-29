# customer-navigation (molecule)

Displays sub-page customer navigation links.

## Code sample

```
{% include molecule('customer-navigation', 'CustomerPage') with {
    data: {
        activePage: 'activePage',
        activeEntityId: activeEntityId
    }
} only %}
```
