Displays a list of business unit addresses in the current business unit.

## Code sample

```
{% include molecule('business-unit-address-list', 'CompanyPage') with {
    data: {
        field: field,
        addresses: addresses,
        idCompanyBusinessUnit: idCompanyBusinessUnit
    }
} only %}
```
