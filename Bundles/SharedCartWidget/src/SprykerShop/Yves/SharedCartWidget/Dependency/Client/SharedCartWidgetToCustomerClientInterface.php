<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SharedCartWidget\Dependency\Client;

interface SharedCartWidgetToCustomerClientInterface
{
    /**
     * Specification:
     * - Returns customer information from session.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    public function getCustomer();
}