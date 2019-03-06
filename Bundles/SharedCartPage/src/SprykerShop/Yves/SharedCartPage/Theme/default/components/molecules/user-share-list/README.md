Displays a list of users with which a chosen shopping cart can be shared, granting certain access rights.

## Code sample

```
{% include molecule('user-share-list', 'SharedCartPage') with {
    data: {
        shareDetailsForm: shareDetailsForm,
        shared: shared
    }
} only %}
```
