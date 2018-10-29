# business-unit-table (molecule)

Displays detail business unit table, with actions (view, edit, delete).

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
