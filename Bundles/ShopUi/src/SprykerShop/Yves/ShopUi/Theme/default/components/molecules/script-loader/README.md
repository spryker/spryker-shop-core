# script-loader (molecule)

Provides an async loading scripts to the DOM.

## Code sample

```
{% include molecule('script-loader') with {
    attributes: {
        src: data.scriptSrc
    }
} only %}
```
