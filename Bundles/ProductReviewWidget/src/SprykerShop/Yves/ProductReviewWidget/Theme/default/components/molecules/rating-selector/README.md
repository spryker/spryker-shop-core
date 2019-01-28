Displays rating represented by stars (usually 1–5), the highest number of stars indicating the best quality etc.

## Code sample

```
{% include molecule('rating-selector', 'ProductReviewWidget') with {
    data: {
        value: data.value,
        maxValue: data.maxValue,
        useHalfSteps: true
    },
    attributes: {
        readonly: true
    }
} only %}
```
