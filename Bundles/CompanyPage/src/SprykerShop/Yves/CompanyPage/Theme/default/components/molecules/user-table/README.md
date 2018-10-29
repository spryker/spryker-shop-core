# user-table (molecule)

Displays user table.

## Code sample

```
{% include molecule('user-table', 'CompanyPage') with {
    data: {
        users: users,
        currentCompanyUserId: currentCompanyUserId,
        actions: {
            update: false,
            delete: false
        }
    }
} only %}
```
