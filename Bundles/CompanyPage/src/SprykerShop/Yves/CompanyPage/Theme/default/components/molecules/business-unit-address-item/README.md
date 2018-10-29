# business-unit-address-item (molecule)

Displays address with edit and delete links.

## Code sample

```
{% include molecule('business-unit-address-item', 'CompanyPage') with {
    data: {
        address: address,
        idCompanyBusinessUnit: idCompanyBusinessUnit
    }
} only %}
```
