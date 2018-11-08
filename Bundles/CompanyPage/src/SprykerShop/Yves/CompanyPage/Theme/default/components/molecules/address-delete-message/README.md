# address-delete-message (molecule)

Displays a message that allows you to confirm whether you want to delete an address from the business unit or not.

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
