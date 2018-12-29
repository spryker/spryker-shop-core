# simple-carousel (molecule)

Creates a carousel from an array of items, generating left and right arrows, as well as optional dots control.

## Code sapmle

```
{% include molecule('simple-carousel') with {
    data: {
        slides: slides,
        showDots: true
    },
    attributes: {
        'slides-to-show': slides-to-show,
        'slides-to-scroll': slides-to-scroll
    }
} only %}
```
