# role-user-table (molecule)

Displays users role table.

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
