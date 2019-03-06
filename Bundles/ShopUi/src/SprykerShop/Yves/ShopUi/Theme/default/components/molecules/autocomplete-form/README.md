Enables users to quickly find and select from a pre-populated list of values as they type, leveraging searching and filtering.

## Code sample

```
{% include molecule('autocomplete-form') with {
    attributes: {
        'suggestion-url': 'suggestion-url',
        'selected-value-key': 'selected-value-key',
        'value-data-attribute': 'value-data-attribute',
        'item-selector': 'item-selector',
        'show-clean-button': false
    }
} only %}
```
