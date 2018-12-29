# review-distribution-display (molecule)

Represents a list of percentage bars showing how many votes each option has.

## Code sample

```
{% include molecule('review-distribution-display', 'ProductReviewWidget') with {
    data: {
        summary: data.summary
    }
} only %}
```
