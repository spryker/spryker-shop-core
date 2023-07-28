<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShipmentTypeWidget;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class ShipmentTypeWidgetConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Shared\ShipmentType\ShipmentTypeConfig::SHIPMENT_TYPE_DELIVERY
     *
     * @var string
     */
    protected const SHIPMENT_TYPE_DELIVERY = 'delivery';

    /**
     * Specification:
     * - Defines the default shipment type that will be used in the shipment type address form.
     *
     * @api
     *
     * @return string
     */
    public function getDefaultShipmentType(): string
    {
        return static::SHIPMENT_TYPE_DELIVERY;
    }
}
