Displays invitation links as a table, which contains company users info, and action links (send, resend, delete).

## Code sample

```
{% include molecule('invitation-link', 'CompanyUserInvitationPage') with {
    data: {
        invitations: invitations
    }
} only %}
```
