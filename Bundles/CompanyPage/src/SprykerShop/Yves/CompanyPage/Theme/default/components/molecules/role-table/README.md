# role-table (molecule)

Displays table roles.

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
