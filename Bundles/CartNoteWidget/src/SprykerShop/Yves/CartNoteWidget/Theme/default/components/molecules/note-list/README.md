Shows unordered list with optional label as first item and text note as second.

## Code sample

```
{% include molecule('note-list', 'CartNoteWidget') with {
    data: {
        label: data.label,
        note: data.note
    }
} only %}
```
