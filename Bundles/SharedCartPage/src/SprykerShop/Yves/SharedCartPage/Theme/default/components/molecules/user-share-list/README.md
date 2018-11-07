# user-share-list (molecule)

Displays a share list of user, which you can share shopping cart with certain rights.

## Code sample

```
{% include molecule('user-share-list', 'SharedCartPage') with {
    data: {
        shareDetailsForm: shareDetailsForm,
        shared: shared
    }
} only %}
```
