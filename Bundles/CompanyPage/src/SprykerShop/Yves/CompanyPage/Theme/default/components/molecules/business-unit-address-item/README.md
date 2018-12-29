# business-unit-address-item (molecule)

On business unit page, displays assigned and unassigned addresses which can be edited or deleted.

## Code sample

```
{% include molecule('business-unit-address-item', 'CompanyPage') with {
    data: {
        address: address,
        idCompanyBusinessUnit: idCompanyBusinessUnit
    }
} only %}
```
