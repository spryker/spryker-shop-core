# business-unit-address-item (molecule)

Displays address, where you can edit or delete address.

## Code sample

```
{% include molecule('business-unit-address-item', 'CompanyPage') with {
    data: {
        address: address,
        idCompanyBusinessUnit: idCompanyBusinessUnit
    }
} only %}
```
