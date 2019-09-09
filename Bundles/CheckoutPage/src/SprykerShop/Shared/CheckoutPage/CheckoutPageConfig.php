<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Shared\CheckoutPage;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class CheckoutPageConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Shipment expense type name.
     *
     * @api
     *
     * @see \Spryker\Shared\Shipment\ShipmentConfig::SHIPMENT_EXPENSE_TYPE.
     */
    public const SHIPMENT_EXPENSE_TYPE = 'SHIPMENT_EXPENSE_TYPE';
}
