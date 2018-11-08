# product-cms-block (molecule)

Shows CMS-block content.

## Code sample

```
{% include molecule('product-cms-block', 'CmsBlockWidget') with {
    data: {
        idProductAbstract: data.product.idProductAbstract
    }
} only %}
```
