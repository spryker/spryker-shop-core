# business-unit-delete-message (molecule)

Displays message, when you want to delete business unit.

## Code sample

```
{% include molecule('business-unit-delete-message', 'CompanyPage') with {
    data: {
        companyBusinessUnit: companyBusinessUnit
    }
} only %}
```
