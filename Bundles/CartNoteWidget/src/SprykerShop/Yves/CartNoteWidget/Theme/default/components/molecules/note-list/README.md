Shows unordered list with an optional label as the first item and a text note as the second.

## Code sample

```
{% include molecule('note-list', 'CartNoteWidget') with {
    data: {
        label: data.label,
        note: data.note
    }
} only %}
```
