# role-table (molecule)

Displays table roles, with actions (view, update, delete) if it actions enabled.

## Code sample

```
{% include molecule('role-table', 'CompanyPage') with {
    data: {
        roles: data.roles,
        actions: {
            view: true,
            update: false,
            delete: false
        }
    }
} only %}
```
