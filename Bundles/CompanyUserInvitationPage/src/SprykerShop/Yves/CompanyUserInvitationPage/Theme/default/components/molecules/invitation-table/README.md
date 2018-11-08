# invitation-link (molecule)

Displays an invitation links as table, which contains a company users info, and action links (send, resend, delete).

## Code sample

```
{% include molecule('invitation-link', 'CompanyUserInvitationPage') with {
    data: {
        invitations: invitations
    }
} only %}
```
