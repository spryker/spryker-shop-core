<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin;

interface AfterCustomerAuthenticationSuccessPluginInterface
{
    /**
     * Specification:
     *  - Plugin is executed after the customer is successfully authenticated.
     *
     * @api
     *
     * @return void
     */
    public function execute(): void;
}
