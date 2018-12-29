# company-navigation (molecule)

Displays the company navigation links sub-page.

## Code sample

```
{% include molecule('company-navigation', 'CompanyPage') with {
    data: {
        activePage: 'activePage'
    }
} only %}
```
