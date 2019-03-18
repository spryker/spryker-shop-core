A "breadcrumb" is a type of the secondary navigation scheme that reveals user’s location on a website.

## Code sample

```
{% include molecule('breadcrumb') with {
    data: {
        steps: [{
            label: 'label',
        },
        {
            label: 'label',
        }],
        startWithHome: true
    }
} only %}
```
