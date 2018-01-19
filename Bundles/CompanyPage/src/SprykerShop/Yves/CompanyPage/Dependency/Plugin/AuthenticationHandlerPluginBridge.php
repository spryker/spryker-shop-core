<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Dependency\Plugin;

use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;

class AuthenticationHandlerPluginBridge implements AuthenticationHandlerPluginInterface
{
    /**
     * @var \SprykerShop\Yves\CustomerPage\Plugin\AuthenticationHandler
     */
    protected $authenticationHandlerPlugin;

    /**
     * @param \SprykerShop\Yves\CustomerPage\Plugin\AuthenticationHandler $authenticationHandlerPlugin
     */
    public function __construct($authenticationHandlerPlugin)
    {
        $this->authenticationHandlerPlugin = $authenticationHandlerPlugin;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function registerCustomer(CustomerTransfer $customerTransfer): CustomerResponseTransfer
    {
        return $this->authenticationHandlerPlugin->registerCustomer($customerTransfer);
    }
}