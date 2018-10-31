# user-share-list (molecule)

Displays user share list in my account, where you can change access.

## Code sample

```
{% include molecule('user-share-list', 'SharedCartPage') with {
    data: {
        shareDetailsForm: shareDetailsForm,
        shared: shared
    }
} only %}
```
