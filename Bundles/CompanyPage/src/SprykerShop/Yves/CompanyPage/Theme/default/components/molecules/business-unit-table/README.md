# business-unit-table (molecule)

Displays a business unit details as table with action links (view, edit, delete).

## Code sample

```
{% include molecule('business-unit-table', 'CompanyPage') with {
    data: {
        businessUnits: businessUnits,
        actions: {
            view: true,
            update: false,
            delete: false
        }
    }
} only %}
```
