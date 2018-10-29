# company-navigation (molecule)

Displays sub-page company navigation links.

## Code sample

```
{% include molecule('company-navigation', 'CompanyPage') with {
    data: {
        activePage: 'activePage'
    }
} only %}
```
