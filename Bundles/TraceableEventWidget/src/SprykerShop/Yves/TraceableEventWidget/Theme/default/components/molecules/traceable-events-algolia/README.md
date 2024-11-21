# Traceable Events Algolia Adapter

## Overview

The **TraceableEventsAlgolia** adapter is an integration with Algolia's Insights API. It allows you to track and send specific events to Algolia, such as product clicks, views, and purchases, within a **Traceable Events Orchestrator** setup. This adapter is designed to work with Algolia’s e-commerce analytics capabilities, enabling you to monitor user interactions and conversions effectively.

## Available Events

-   **Product Click Tracking**: Captures data on product clicks, including SKU, position, and search query details (`PRODUCT_CLICK`).
-   **Filter Click Tracking**: Tracks when users click on filters (`FILTER_CLICK`).
-   **View Tracking**: Monitors product views on pages like PDP (Product Detail Page) and CATALOG (`PAGE_LOAD`).
-   **Add to Cart Tracking**: Tracks items added to the cart, including SKU, pricing, and quantity (`ADD_TO_CART`).
-   **Wishlist and Shopping List Events**: Tracks when products are added to the wishlist or shopping list (`ADD_TO_WISHLIST`, `ADD_TO_SHOPPING_LIST`).
-   **Purchase Tracking**: Records completed purchases on the checkout success page (`PAGE_LOAD`).
