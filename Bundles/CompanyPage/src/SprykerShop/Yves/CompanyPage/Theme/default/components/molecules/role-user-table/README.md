# role-user-table (molecule)

Displays users role table, with actions (unassign, assign) if it actions enabled.

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
