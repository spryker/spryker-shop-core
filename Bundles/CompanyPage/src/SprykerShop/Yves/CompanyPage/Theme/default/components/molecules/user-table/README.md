# user-table (molecule)

Displays a company user details as table with action links (edit, disable, enable, delete).

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
