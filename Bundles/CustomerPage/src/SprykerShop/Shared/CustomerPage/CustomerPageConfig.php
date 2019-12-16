<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Shared\CustomerPage;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class CustomerPageConfig extends AbstractSharedConfig
{
    public const SECURITY_FIREWALL_NAME = 'secured';

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
