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
     * - Defines a list of properties in a `ItemTransfer` that are not intended for form hydration.
     * - Items with such properties should not be included in the form hydration process, as they are not relevant to the `ServicePointAddressStepForm`.
     *
     * @api
     *
     * @return list<string>
     */
    public function getNotApplicableServicePointAddressStepFormItemPropertiesForHydration(): array
    {
        return [];
    }

    /**
     * Specification:
     * - Defines a list of shipment type keys that are considered as delivery types.
     * - Used to determine if a shipment type is a delivery type in the service point widget.
     *
     * @api
     *
     * @return list<string>
     */
    public function getDeliveryShipmentTypeKeys(): array
    {
        return [
            static::SHIPMENT_TYPE_DELIVERY,
        ];
    }
}
