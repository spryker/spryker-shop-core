Displays company roles as a table with action links (view, update, delete).

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
