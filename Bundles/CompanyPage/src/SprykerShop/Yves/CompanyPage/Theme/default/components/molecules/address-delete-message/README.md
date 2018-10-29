# address-delete-message (molecule)

Displays message, when you want to delete address from business unit, where you can confirm delete address or cancel delete address.

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
