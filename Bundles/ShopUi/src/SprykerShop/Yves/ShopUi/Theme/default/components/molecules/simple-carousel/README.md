# simple-carousel (molecule)

Creates a carousel from an array of items, generates the left and right arrows as well as the optional dots control.

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
