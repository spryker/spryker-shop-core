# role-user-table (molecule)

Displays company user roles as a table with action links (unassign, assign).

## Code sample

```
{% include molecule('role-user-table', 'CompanyPage') with {
    data: {
        users: users,
        idCompanyRole: idCompanyRole,
        actions: {
            switch: true
        }
    }
} only %}
```
