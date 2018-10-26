# checkbox (atom)

Creates a simple checkbox element with not native design.

## Code sample 

```
{% include atom('checkbox') with {
     attributes: {
         name: 'name',
         value: 1,
         checked: true
     },
     data: {
         label: label
     }
 } only %}
 ```
