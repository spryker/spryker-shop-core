# company-navigation (molecule)

Displays navigation in company page.

## Code sample

```
{% include molecule('company-navigation', 'CompanyPage') with {
    data: {
        activePage: 'activePage'
    }
} only %}
```
