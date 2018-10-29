# business-unit-delete-message (molecule)

Displays message, when you want to delete business unit, where you can confirm delete business unit or cancel.

## Code sample

```
{% include molecule('business-unit-delete-message', 'CompanyPage') with {
    data: {
        companyBusinessUnit: companyBusinessUnit
    }
} only %}
```
