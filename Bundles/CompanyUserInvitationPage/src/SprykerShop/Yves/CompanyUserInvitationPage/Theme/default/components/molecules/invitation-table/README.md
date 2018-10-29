# invitation-link (molecule)

Displays innovation table, which contains information about people, with actions(send, resend, delete), if it actions enabled.

## Code sample

```
{% include molecule('invitation-link', 'CompanyUserInvitationPage') with {
    data: {
        invitations: invitations
    }
} only %}
```
