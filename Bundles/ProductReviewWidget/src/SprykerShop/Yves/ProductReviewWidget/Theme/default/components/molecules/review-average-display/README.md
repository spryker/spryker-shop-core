# review-average-display (molecule)

Displays an average rating based on 5-option voting. The average rating calculator takes a number of votes for each option (1 - 5 stars) and gives you the mean rating.

## Code sample

```
{% include molecule('review-average-display', 'ProductReviewWidget') with {
    data: {
        summary: data.summary,
        ratingMaxValue: data.ratingMaxValue
    }
} only %}
```
