# permission-table (molecule)

Displays table roles permission, with actions (configure, unassign, assign) if it actions enabled.

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
