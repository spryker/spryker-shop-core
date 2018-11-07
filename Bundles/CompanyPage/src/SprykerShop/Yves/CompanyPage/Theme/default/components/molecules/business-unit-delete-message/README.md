# business-unit-delete-message (molecule)

Displays a message that allows you to confirm whether you want to delete the business unit or not.

## Code sample

```
{% include molecule('business-unit-delete-message', 'CompanyPage') with {
    data: {
        companyBusinessUnit: companyBusinessUnit
    }
} only %}
```
