# user-table (molecule)

Displays company user details as a table with action links(edit, disable, enable, delete).

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
