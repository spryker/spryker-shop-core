# address-delete-message (molecule)

Displays message, when you want to delete address from business unit.

## Code sample

```
{% include molecule('address-delete-message', 'CompanyPage') with {
    data: {
        companyUnitAddress: 'companyUnitAddress',
        companyBusinessUnits: 'companyBusinessUnits',
        idCompanyBusinessUnit: idCompanyBusinessUnit,
        cancelUrl: 'cancelUrl'
    }
} only %}
```
