# ajax-provider (molecule)

Enables ajax requests to be performed to a specified url with URL parameters and response handling.

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
