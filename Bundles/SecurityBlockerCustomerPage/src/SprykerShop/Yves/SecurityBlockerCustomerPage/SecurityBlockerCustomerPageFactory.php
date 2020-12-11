<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SecurityBlockerCustomerPage;

use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Router\Router\RouterInterface;
use SprykerShop\Yves\SecurityBlockerCustomerPage\Dependency\Client\SecurityBlockerCustomerPageToSecurityBlockerClientInterface;
use SprykerShop\Yves\SecurityBlockerCustomerPage\EventSubscriber\FailedLoginMonitoringEventSubscriber;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @method \SprykerShop\Yves\SecurityBlockerCustomerPage\SecurityBlockerCustomerPageConfig getConfig()
 */
class SecurityBlockerCustomerPageFactory extends AbstractFactory
{
    /**
     * @return \Symfony\Component\EventDispatcher\EventSubscriberInterface
     */
    public function createFailedLoginMonitoringEventSubscriber(): EventSubscriberInterface
    {
        return new FailedLoginMonitoringEventSubscriber(
            $this->getRequestStack(),
            $this->getRouter(),
            $this->getSecurityBlockerClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\SecurityBlockerCustomerPage\Dependency\Client\SecurityBlockerCustomerPageToSecurityBlockerClientInterface
     */
    public function getSecurityBlockerClient(): SecurityBlockerCustomerPageToSecurityBlockerClientInterface
    {
        return $this->getProvidedDependency(SecurityBlockerCustomerPageDependencyProvider::CLIENT_SECURITY_BLOCKER);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RequestStack
     */
    public function getRequestStack(): RequestStack
    {
        return $this->getProvidedDependency(SecurityBlockerCustomerPageDependencyProvider::REQUEST_STACK);
    }

    /**
     * @return \Spryker\Yves\Router\Router\RouterInterface
     */
    public function getRouter(): RouterInterface
    {
        return $this->getProvidedDependency(SecurityBlockerCustomerPageDependencyProvider::ROUTER);
    }
}
