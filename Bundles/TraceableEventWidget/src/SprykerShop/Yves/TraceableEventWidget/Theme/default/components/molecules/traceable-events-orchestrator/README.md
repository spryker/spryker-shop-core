# Traceable Events Orchestrator

## Overview

The **Traceable Events Orchestrator** is a dynamic event-handling system that allows you to track specific user interactions within your application and respond to them through configurable event triggers. This orchestrator uses adapters to modularize and manage event handlers, making it ideal for scenarios requiring complex user tracking, such as e-commerce applications.

## Features

-   **Event Orchestration**: Track and respond to a wide range of user interactions.
-   **Adapter-Based Architecture**: Modular adapters allow for specific handling of individual event types.
-   **Customizable Event Triggers**: Define triggers using CSS selectors, exceptions, and relative targets.
-   **Data Capture**: Collect contextual information from DOM elements based on event triggers.
-   **Twig Integration**: Configure events directly within Twig templates.

## Components

### Traceable Events Orchestrator

This is the core class that listens to and manages events. Event configurations are set up in Twig, allowing the orchestrator to track specific elements and interactions and pass data to registered adapters.

### BaseTraceableEventAdapter

Adapters are used to handle specific events. They register with the orchestrator and are notified when relevant events occur, allowing custom logic for each event type.

## Getting Started

### Step 1: Define Events in Twig and global data.

Define the events and their configurations in your Twig template using the `EventData` format. Each event should specify (events.list):

-   **Event Type**: The type of interaction to track (e.g., `PAGE_LOAD`, `PRODUCT_CLICK`).
-   **Event Name**: An identifier for the event.
-   **Triggers**: Specific conditions that must be met for the event to trigger.

Define any global data that will be available across all events. (events.data)

Example:

```twig
{% set events = {
    list: [
        {
            event: 'PAGE_LOAD',
            name: 'load',
        },
        {
            event: 'PRODUCT_CLICK',
            name: 'click',
            triggers: [
                {
                    selector: '.js-product-item:not(:has(.js-ajax-add-to-cart__button:focus))',
                    groupAs: {
                        key: 'products',
                        toArray: true,
                    },
                    data: {
                        details: {
                            selector: 'self',
                            flatten: true,
                        },
                    },
                },
                {
                    selector: '.suggestion-product',
                    groupAs: {
                        key: 'products',
                        toArray: true,
                    },
                    data: {
                        sku: {
                            selector: 'self',
                        },
                        searchId: {
                            value: null,
                        },
                    },
                }
            ],
        },
    ],
    data: {
        currency: currency | spaceless,
    },
} %}
```

### Step 2: Use Traceable Event Widget

Embed the `TraceableEventWidget` in your template with the configured events and global data:

```twig
{% block eventTracker %}
    {% widget 'TraceableEventWidget' with {
        events: events,
    } only %}
{% endwidget %}
```

## Creating Adapters

Adapters are classes that extend the `BaseTraceableEventAdapter` and define specific logic to handle various types of events. Below is an example and explanation of creating an adapter, `TraceableEventsAlgolia`, which integrates with the Algolia Insights API to track specific events.

### Example: TraceableEventsAlgolia Adapter

```typescript
declare global {
    interface GlobalEventData {
        // Augment global data with params
    }

    interface SPRYKER_EVENTS {
        // Add additional custom events
    }
}

interface ProductEventData extends EventsHandlerData<EventName> {
    // Add params for specific event
}

export default class TraceableEventsAdapter extends BaseTraceableEventAdapter {
    override init(): void {
        // Custom initialization logic (optional)

        super.init();
    }

    override getHandlers(): Partial<TraceableEventHandlers> {
        return {
            PRODUCT_CLICK: [this.productClickHandler],
            // Other events
        };
    }

    protected productClickHandler(data: ProductEventData): void {
        // Custom handler logic for product click events
    }
}
```

### Steps to Create an Adapter

1. **Extend BaseTraceableEventAdapter**: Start by extending `BaseTraceableEventAdapter` to inherit core functionalities.
2. **Initialize Custom Logic**: Override the `init` method to initialize any third-party services or set up data.
3. **Define Handlers in getHandlers()**: Override `getHandlers()` to specify which methods handle specific events. Each handler is mapped to an event type (e.g., `PRODUCT_CLICK`, `PAGE_LOAD`).
4. **Implement Event Handlers**: Each handler method should process event data and perform the necessary actions, such as sending data to a tracking service.

### Adapter Extending on Project Level

If you need to extend existing events with custom logic or/and add new event you can extend adapter on project level, see example below.

```typescript
declare global {
    interface SPRYKER_EVENTS {
        NEW_EVENT: undefined;
    }
}

interface EventData extends EventsHandlerData<EventName> {
    // Add params for specific event
}

export default class ProjectEventsAdapter extends TraceableEventsCoreAdapter {
    override getHandlers(): Partial<TraceableEventHandlers> {
        const events = super.getHandlers();

        return {
            ...events,
            PRODUCT_CLICK: [...events['PRODUCT_CLICK'], this.additionalLogicClickEvent],
            NEW_EVENT: [this.projectEvent],
        };
    }

    protected projectEvent(data: EventData): void {
        // Custom handler for new event
    }

    protected additionalLogicClickEvent(data: ProductEventData): void {
        // Custom handler for product click event
    }
}
```

Then you can add it to twig events configuration.

```twig
{% block eventTracker %}
    {% set events = {
        list: events.list | merge([{
            event: 'NEW_EVENT',
            name: event handler name,
            triggers: [...event triggers],
        }]),
        data: events.data,
    } %}

    {{ parent() }}
{% endblock %}
```

## Event Configuration Guide

### Event Structure

-   **`event`**: Specifies the event type (e.g., `PAGE_LOAD`, `PRODUCT_CLICK`).
-   **`name`**: A case-sensitive string representing the event type to listen for (`click`, `change`, `load`).
-   **`triggers`**: An array of conditions for capturing the event.
    -   **`selector`**: CSS selector for the element to monitor.
    -   **`groupAs`**: Groups data into a key, optionally as an array.
    -   **`data`**: Data points to capture, supporting:
        -   **`selector`**: Element from which to extract data (`'self'` for the target).
        -   **`flatten`**: Merges nested data into a single object.
        -   **`attribute`**: The attribute to retrieve (default: key name).
        -   **`value`**: A static value if defined.
        -   **`multi`**: Specifies whether to capture multiple matching elements.
        -   **`composed`**: Nested structure to compose data objects.

### Example: Event Configuration

```twig
{% set events = {
    list: events.list | merge([{
        event: 'EVENT_EXAMPLE', // The name of the event to be tracked
        name: 'click', // The type of user interaction that triggers the event
        triggers: [
            {
                selector: '.js-related-products', // The CSS selector for the element to monitor for user interaction
                groupAs: {
                    key: 'relatedProducts', // Group data under the 'relatedProducts' key
                    toArray: true, // Convert the grouped data into an array format
                },
                data: {
                    details: {
                        selector: 'self', // Look for the 'details' attribute within the current element
                        flatten: true, // Flatten the structure of the object to simplify it
                    },
                    name: {
                        selector: '.product-name', // Search for an element with the 'product-name' class within the monitored element
                        attribute: 'price', // Use the 'price' attribute as the value; if absent, fallback to the element's text content
                    },
                    price: {
                        value: 'static value', // Assign a fixed value to the 'price' attribute
                    },
                    attributes: {
                        selector: '.attribute-selector',
                        multi: true, // Collect all matching elements and return their data as an array
                    },
                    metadata: {
                        multi: true,
                        selector: '.metadata-row',
                        composed: { // Create nested structures for more detailed data gathering and start searching elements from `.metadata-row` selector.
                            brand: {
                                selector: '.product-brand',
                                attribute: 'textContent'
                            },
                            category: {
                                selector: '.product-category',
                            },
                        },
                    },
                },
            },
        ],
    }]),
    data: events.data,
} %}

{# Expected transformed data format in the console:
  {
      ...global data/event metadata,
      relatedProducts: {
          // Flattened data from the 'details' attribute
          name: VALUE, // The value taken from the 'name' selector or attribute
          price: 'static value', // The fixed 'price' value
          attributes: [VALUE, VALUE, VALUE, ...], // Array of values collected from elements matching '.attribute-selector'
          metadata: [
              {
                  brand: VALUE, // 'brand' data extracted from the '.metadata-row .product-brand' element
                  category: VALUE, // 'category' data from the '.metadata-row .product-category' element
              },
              {
                  brand: VALUE, // 'brand' data extracted from the '.metadata-row .product-brand' element
                  category: VALUE, // 'category' data from the '.metadata-row .product-category' element
              },
              ...
          ]
      }
  }
#}
```

## Data Flow

1. **Event Trigger**: When a defined event trigger is met, the orchestrator captures data based on the trigger configuration.
2. **Data Processing**: The orchestrator gathers data points specified in the configuration, using attributes or default values.
3. **Event Handling**: Adapters registered to the orchestrator receive the event data for further processing.

## Debug Mode

Enable debugging by setting the `debug` attribute in the Twig template. This logs event details to the console for inspection.

```twig
{% define attributes = {
    debug: true,
} %}
```

## Error Handling

The orchestrator logs errors during event handling to the console, allowing for easy troubleshooting.
