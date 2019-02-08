Shows a CMS-block content.

## Code sample

```
{% include molecule('catalog-cms-block', 'CmsBlockWidget') with {
    data: {
        idCategory: data.category.idCategory,
        position: 'top'
    }
} only %}
```
