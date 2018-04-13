<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Dependency\Plugin;

use Generated\Shared\Transfer\CustomerTransfer;

interface PostCustomerRegistrationPluginInterface
{
    /**
     * This plugin allows to execute additional actions after customer registration.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function execute(CustomerTransfer $customerTransfer): CustomerTransfer;
}
