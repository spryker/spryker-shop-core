# review-distribution-display (molecule)

Displays as a percentage how much each assessment takes.

## Code sample

```
{% include molecule('review-distribution-display', 'ProductReviewWidget') with {
    data: {
        summary: data.summary
    }
} only %}
```
