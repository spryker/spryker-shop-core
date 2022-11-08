<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SessionAgentValidation\Plugin\Security;

use Spryker\Shared\SecurityExtension\Dependency\Plugin\SecurityAuthenticationListenerFactoryTypeExpanderPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \SprykerShop\Yves\SessionAgentValidation\SessionAgentValidationFactory getFactory()
 */
class SessionAgentValidationSecurityAuthenticationListenerFactoryTypeExpanderPlugin extends AbstractPlugin implements SecurityAuthenticationListenerFactoryTypeExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands security authentication listener factory types list with agent's session validator factory type.
     *
     * @api
     *
     * @param array<string> $authenticationListenerFactoryTypes
     *
     * @return list<string>
     */
    public function expand(array $authenticationListenerFactoryTypes): array
    {
        return $this->getFactory()
            ->createSecurityAuthenticationListenerFactoryTypeExpander()
            ->expand($authenticationListenerFactoryTypes);
    }
}
