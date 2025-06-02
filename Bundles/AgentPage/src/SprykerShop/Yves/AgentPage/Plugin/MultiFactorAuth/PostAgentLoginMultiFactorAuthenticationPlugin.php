<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentPage\Plugin\MultiFactorAuth;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\PostLoginMultiFactorAuthenticationPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Shared\CustomerPage\CustomerPageConfig as SharedCustomerPageConfig;
use Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken;

/**
 * @method \SprykerShop\Yves\AgentPage\AgentPageFactory getFactory()
 */
class PostAgentLoginMultiFactorAuthenticationPlugin extends AbstractPlugin implements PostLoginMultiFactorAuthenticationPluginInterface
{
    /**
     * @var string
     */
    protected const AGENT_POST_AUTHENTICATION_TYPE = 'AGENT_POST_AUTHENTICATION_TYPE';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $authenticationType
     *
     * @return bool
     */
    public function isApplicable(string $authenticationType): bool
    {
        return $authenticationType === static::AGENT_POST_AUTHENTICATION_TYPE;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $email
     *
     * @return void
     */
    public function createToken(string $email): void
    {
        $user = $this->getFactory()->createAgentUserProvider()->loadUserByIdentifier($email);

        $token = new PostAuthenticationToken(
            $user,
            SharedCustomerPageConfig::SECURITY_FIREWALL_NAME,
            $user->getRoles(),
        );
        $tokenStorage = $this->getFactory()->getTokenStorage();
        $tokenStorage->setToken($token);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $userTransfer
     *
     * @return void
     */
    public function executeOnAuthenticationSuccess(AbstractTransfer $userTransfer): void
    {
        $this->getFactory()->createAgentAuthenticationSuccessHandler()->executeOnAuthenticationSuccess($userTransfer);
    }
}
