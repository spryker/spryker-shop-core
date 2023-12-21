<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentPage\Plugin\Security;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin\AfterCustomerAuthenticationSuccessPluginInterface;

/**
 * @method \SprykerShop\Yves\AgentPage\AgentPageFactory getFactory()
 */
class UpdateAgentTokenAfterCustomerAuthenticationSuccessPlugin extends AbstractPlugin implements AfterCustomerAuthenticationSuccessPluginInterface
{
    /**
     * {@inheritDoc}
     * - Detects if the user has the `ROLE_PREVIOUS_ADMIN` role.
     * - Checks if both the agent and customer are logged in.
     * - Ensures that the current user is an instance of the `Customer` class.
     * - Updates agent token after customer authentication success.
     *
     * @api
     *
     * @return void
     */
    public function execute(): void
    {
        $this->getFactory()->createAgentTokenAfterCustomerAuthenticationSuccessUpdater()->execute();
    }
}
