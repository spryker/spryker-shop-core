# business-unit-address-item (molecule)

Displays assigned and unassigned addresses on business unit page, which you can edit or delete.

## Code sample

```
{% include molecule('business-unit-address-item', 'CompanyPage') with {
    data: {
        address: address,
        idCompanyBusinessUnit: idCompanyBusinessUnit
    }
} only %}
```
