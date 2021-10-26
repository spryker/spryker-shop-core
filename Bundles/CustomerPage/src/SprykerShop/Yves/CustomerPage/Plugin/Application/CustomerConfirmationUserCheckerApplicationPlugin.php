<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Plugin\Application;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\Security\Core\User\UserCheckerInterface;

/**
 * @method \Spryker\Client\Customer\CustomerClientInterface getClient()
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageConfig getConfig()
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageFactory getFactory()
 */
class CustomerConfirmationUserCheckerApplicationPlugin extends AbstractPlugin implements ApplicationPluginInterface
{
    /**
     * @uses \Spryker\Yves\Security\Plugin\Application\SecurityApplicationPlugin::SERVICE_SECURITY_USER_CHECKER
     *
     * @var string
     */
    protected const SERVICE_SECURITY_USER_CHECKER = 'security.user_checker';

    /**
     * {@inheritDoc}
     * - Sets a new user checker that checks if customer confirmed registration.
     * - Executes `PreAuthUserCheckPluginInterface` plugin stack.
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function provide(ContainerInterface $container): ContainerInterface
    {
        $container->extend(static::SERVICE_SECURITY_USER_CHECKER, function (UserCheckerInterface $userChecker) {
            return $this->getFactory()->createCustomerConfirmationUserChecker();
        });

        return $container;
    }
}
