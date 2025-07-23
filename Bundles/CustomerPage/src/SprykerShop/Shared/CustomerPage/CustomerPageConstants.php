<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Shared\CustomerPage;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface CustomerPageConstants
{
    /**
     * @var string
     */
    public const CUSTOMER_REMEMBER_ME_SECRET = 'CUSTOMER_PAGE:CUSTOMER_REMEMBER_ME_SECRET';

    /**
     * @var string
     */
    public const CUSTOMER_REMEMBER_ME_LIFETIME = 'CUSTOMER_PAGE:CUSTOMER_REMEMBER_ME_LIFETIME';

    /**
     * Specification:
     * - Returns true if the store routing is enabled.
     *
     * @api
     *
     * @var string
     */
    public const IS_STORE_ROUTING_ENABLED = 'CUSTOMER_PAGE:IS_STORE_ROUTING_ENABLED';
}
