# review-summary (organism)

Displays a section with a list of reviews, average, and detailed rating.

## Code sample

```
{% include organism('review-summary', 'ProductReviewWidget') with {
    data: {
        reviews: data.reviews,
        summary: data.summary,
        ratingMaxValue: data.ratingMaxValue,
        hasCustomer: data.hasCustomer,
        pagination: data.pagination
    }
} only %}
```
