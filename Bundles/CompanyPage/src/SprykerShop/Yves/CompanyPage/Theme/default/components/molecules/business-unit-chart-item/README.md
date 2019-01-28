Displays a business unit chart item link.

## Code sample

```
{% include molecule('business-unit-chart-item', 'CompanyPage') with {
     data: {
         node: node,
         level: level
     }
 } only %}
```
