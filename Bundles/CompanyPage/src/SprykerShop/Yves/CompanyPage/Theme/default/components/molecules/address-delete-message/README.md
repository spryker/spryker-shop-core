Displays a business unit address removal confirmation dialog box.

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
