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
     * @deprecated Use {@link \SprykerShop\Yves\ShipmentTypeWidget\ShipmentTypeWidgetConfig::getDeliveryShipmentTypes()} instead.
     *
     * @return string
     */
    public function getDefaultShipmentType(): string
    {
        return static::SHIPMENT_TYPE_DELIVERY;
    }

    /**
     * Specification:
     * - Defines a list of shipment type keys that are considered as delivery types, that will be used in the shipment type address form.
     * - The first shipment type in the list will be considered as the primary default.
     *
     * @api
     *
     * @return list<string>
     */
    public function getDeliveryShipmentTypes(): array
    {
        return [static::SHIPMENT_TYPE_DELIVERY];
    }

    /**
     * Specification:
     * - Defines a list of properties in a `ItemTransfer` that are not intended for form hydration.
     * - Items with such properties should not be included in the form hydration process, as they are not relevant to the `ShipmentTypeAddressStepForm`.
     *
     * @api
     *
     * @return list<string>
     */
    public function getNotApplicableShipmentTypeAddressStepFormItemPropertiesForHydration(): array
    {
        return [];
    }
}
