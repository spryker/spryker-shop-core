Renders a list of company users with their first name, last name, and email.

## Code sample

```
{% include molecule('company-user-list', 'CompanyUserAgentWidget') with {
    data: {
        companyUsers: companyUsers
    }
} only %}
```
