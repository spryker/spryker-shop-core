<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentPageExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CustomerTransfer;

/**
 * Use this plugin interface to execute actions after the session impersonation is started.
 */
interface SessionPostImpersonationPluginInterface
{
    /**
     * Specification:
     * - Executed after the session impersonation.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function execute(CustomerTransfer $customerTransfer): void;
}
