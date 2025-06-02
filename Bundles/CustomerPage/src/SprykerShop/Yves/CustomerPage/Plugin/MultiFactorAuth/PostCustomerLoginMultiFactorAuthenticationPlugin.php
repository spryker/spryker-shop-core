<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Plugin\MultiFactorAuth;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\PostLoginMultiFactorAuthenticationPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Shared\CustomerPage\CustomerPageConfig as SharedCustomerPageConfig;
use Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken;

/**
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageFactory getFactory()
 */
class PostCustomerLoginMultiFactorAuthenticationPlugin extends AbstractPlugin implements PostLoginMultiFactorAuthenticationPluginInterface
{
    /**
     * @var string
     */
    protected const CUSTOMER_POST_AUTHENTICATION_TYPE = 'CUSTOMER_POST_AUTHENTICATION_TYPE';

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
        return $authenticationType === static::CUSTOMER_POST_AUTHENTICATION_TYPE;
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
        $customer = $this->getFactory()->createCustomerUserProvider()->loadUserByIdentifier($email);

        $token = new PostAuthenticationToken(
            $customer,
            SharedCustomerPageConfig::SECURITY_FIREWALL_NAME,
            $customer->getRoles(),
        );
        $tokenStorage = $this->getFactory()->getTokenStorage();
        $tokenStorage->setToken($token);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function executeOnAuthenticationSuccess(AbstractTransfer $customerTransfer): void
    {
        $this->getFactory()->createCustomerAuthenticationSuccessHandler()->executeOnAuthenticationSuccess($customerTransfer);
    }
}
