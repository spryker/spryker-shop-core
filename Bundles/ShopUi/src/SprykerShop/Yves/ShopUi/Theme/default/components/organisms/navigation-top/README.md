Creates a navigation bar with the list of items: price mode switcher, currency switcher, language switcher, Company name and Business Unit name, mini-cart, quick order link, shopping list, company account link, link to customer profile, link to shopping list and login/logout link.

## Code sample

```
{% include organism('navigation-top') with {
    data: {
        isInline: false,
        withSeparators: false
    }
} only %}
```
