# business-unit-address-list (molecule)

Displays a list of business units address in the current business unit.

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
