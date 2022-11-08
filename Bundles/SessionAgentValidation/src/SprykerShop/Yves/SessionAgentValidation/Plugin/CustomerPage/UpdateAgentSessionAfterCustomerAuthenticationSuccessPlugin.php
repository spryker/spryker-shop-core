<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SessionAgentValidation\Plugin\CustomerPage;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin\AfterCustomerAuthenticationSuccessPluginInterface;

/**
 * @method \SprykerShop\Yves\SessionAgentValidation\SessionAgentValidationFactory getFactory()
 */
class UpdateAgentSessionAfterCustomerAuthenticationSuccessPlugin extends AbstractPlugin implements AfterCustomerAuthenticationSuccessPluginInterface
{
    /**
     * {@inheritDoc}
     * - Updates agent's session data in storage if access is granted and an agent is logged in.
     *
     * @api
     *
     * @return void
     */
    public function execute(): void
    {
        $this->getFactory()
            ->createSessionAgentValidationSessionUpdater()
            ->update();
    }
}
