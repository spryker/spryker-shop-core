# permission-table (molecule)

Displays a roles permission as table with action links (configure, unassign, assign).

## Code sample

```
{% include molecule('permission-table', 'CompanyPage') with {
    data: {
        permissions: permissions,
        idCompanyRole: idCompanyRole,
        actions: {
            configure: false,
            switch: false
        }
    }
} only %}
```
