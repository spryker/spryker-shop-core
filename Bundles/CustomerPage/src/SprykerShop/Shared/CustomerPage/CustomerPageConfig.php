<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Shared\CustomerPage;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class CustomerPageConfig extends AbstractSharedConfig
{
    /**
     * @var string
     */
    public const SECURITY_FIREWALL_NAME = 'secured';

    /**
     * Specification:
     * - Shipment expense type name.
     *
     * @api
     *
     * @see \Spryker\Shared\Shipment\ShipmentConfig::SHIPMENT_EXPENSE_TYPE.
     *
     * @var string
     */
    public const SHIPMENT_EXPENSE_TYPE = 'SHIPMENT_EXPENSE_TYPE';

    /**
     * Specification:
     * - URL param specifying the locale that should be used by the target page.
     *
     * @api
     *
     * @uses \Spryker\Shared\Customer\CustomerConfig::URL_PARAM_LOCALE
     *
     * @var string
     */
    public const URL_PARAM_LOCALE = '_locale';
}
