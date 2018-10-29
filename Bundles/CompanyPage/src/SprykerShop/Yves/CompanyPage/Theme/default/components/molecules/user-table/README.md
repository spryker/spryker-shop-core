# user-table (molecule)

Displays user table, with actions (edit, disable, enable, delete) if it actions enabled.

## Code sample

```
{% include molecule('user-table', 'CompanyPage') with {
    data: {
        users: users,
        currentCompanyUserId: currentCompanyUserId,
        actions: {
            update: true,
            delete: true
        }
    }
} only %}
```
