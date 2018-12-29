# review-average-display (molecule)

Displays average rating based on a 5-option voting. Average rating calculation is based on the number of votes for each option (1 - 5 stars).

## Code sample

```
{% include molecule('review-average-display', 'ProductReviewWidget') with {
    data: {
        summary: data.summary,
        ratingMaxValue: data.ratingMaxValue
    }
} only %}
```
