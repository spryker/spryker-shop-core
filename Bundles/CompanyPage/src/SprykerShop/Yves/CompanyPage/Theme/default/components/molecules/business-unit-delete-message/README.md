Displays a business unit removal confirmation dialog box.

## Code sample

```
{% include molecule('business-unit-delete-message', 'CompanyPage') with {
    data: {
        companyBusinessUnit: companyBusinessUnit
    }
} only %}
```
