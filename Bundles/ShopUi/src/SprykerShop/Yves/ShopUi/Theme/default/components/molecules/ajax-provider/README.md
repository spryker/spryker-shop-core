# ajax-provider (molecule)

Enables performing an ajax request to a specified url with some URL parameters and handling the response.

## Code sample

```
{% include molecule('ajax-provider') with {
    attributes: {
       'fetch-on-load': false,
       'url': 'url',
       'method': 'method',
       'response-type': 'response-type'
    }
} only %}
```
