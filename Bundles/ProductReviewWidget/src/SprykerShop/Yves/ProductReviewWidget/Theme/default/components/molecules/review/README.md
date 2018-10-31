# review (molecule)

Displays text of review, which include title, rating, date, and description.

## Code sample

```
{% include molecule('review', 'ProductReviewWidget') with {
    data: {
        summary: review.summary,
        ratingValue: review.ratingValue,
        ratingMaxValue: data.ratingMaxValue,
        nickname: review.nickname,
        createdAt: review.createdAt | formatDateTime,
        description: review.description
    }
} only %}
```
