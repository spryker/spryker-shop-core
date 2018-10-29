# overview-info (molecule)

Displays company overview information.

## Code sample

```
{% include molecule('overview-info', 'CompanyPage') with {
    data: {
        company: 'company',
        defaultBillingAddress: defaultBillingAddress
    }
} only %}
```
