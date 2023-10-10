<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\ClickAndCollectPageExample;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class ClickAndCollectPageExampleConfig extends AbstractBundleConfig
{
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
