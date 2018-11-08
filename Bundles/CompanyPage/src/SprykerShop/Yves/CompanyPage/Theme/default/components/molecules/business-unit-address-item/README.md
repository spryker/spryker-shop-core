# business-unit-address-item (molecule)

Displays an assign addresses, and unassigned addresses on business unit page, which you can edit or delete.

## Code sample

```
{% include molecule('business-unit-address-item', 'CompanyPage') with {
    data: {
        address: address,
        idCompanyBusinessUnit: idCompanyBusinessUnit
    }
} only %}
```
