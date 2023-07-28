<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ServicePointWidget;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class ServicePointWidgetConfig extends AbstractBundleConfig
{
    /**
     * @var int
     */
    protected const SEARCH_RESULT_LIMIT = 10;

    /**
     * @uses \Spryker\Shared\ShipmentType\ShipmentTypeConfig::SHIPMENT_TYPE_DELIVERY
     *
     * @var string
     */
    protected const SHIPMENT_TYPE_DELIVERY = 'delivery';

    /**
     * @uses \Spryker\Shared\ShipmentType\ShipmentTypeConfig::SHIPMENT_TYPE_PICKUP
     *
     * @var string
     */
    protected const SHIPMENT_TYPE_PICKUP = 'pickup';

    /**
     * @var list<string>
     */
    protected const CLICK_AND_COLLECT_SHIPMENT_TYPES = [
        self::SHIPMENT_TYPE_DELIVERY,
        self::SHIPMENT_TYPE_PICKUP,
    ];

    /**
     * Specification:
     * - Defines number of search results returned in single batch.
     * - Used as a fallback.
     *
     * @api
     *
     * @return int
     */
    public function getSearchResultLimit(): int
    {
        return static::SEARCH_RESULT_LIMIT;
    }

    /**
     * Specification:
     * - Returns list of shipment types that can be used for click and collect operations.
     *
     * @api
     *
     * @return list<string>
     */
    public function getClickAndCollectShipmentTypes(): array
    {
        return static::CLICK_AND_COLLECT_SHIPMENT_TYPES;
    }
}
