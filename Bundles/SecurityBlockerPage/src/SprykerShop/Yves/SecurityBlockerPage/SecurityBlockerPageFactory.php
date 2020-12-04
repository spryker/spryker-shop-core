<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SecurityBlockerPage;

use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Router\Router\RouterInterface;
use SprykerShop\Yves\SecurityBlockerPage\Dependency\Client\SecurityBlockerPageToSecurityBlockerClientInterface;
use SprykerShop\Yves\SecurityBlockerPage\EventSubscriber\FailedLoginMonitoringEventSubscriber;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @method \SprykerShop\Yves\SecurityBlockerPage\SecurityBlockerPageConfig getConfig()
 */
class SecurityBlockerPageFactory extends AbstractFactory
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
     * @return \SprykerShop\Yves\SecurityBlockerPage\Dependency\Client\SecurityBlockerPageToSecurityBlockerClientInterface
     */
    public function getSecurityBlockerClient(): SecurityBlockerPageToSecurityBlockerClientInterface
    {
        return $this->getProvidedDependency(SecurityBlockerPageDependencyProvider::CLIENT_SECURITY_BLOCKER);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RequestStack
     */
    public function getRequestStack(): RequestStack
    {
        return $this->getProvidedDependency(SecurityBlockerPageDependencyProvider::REQUEST_STACK);
    }

    /**
     * @return \Spryker\Yves\Router\Router\RouterInterface
     */
    public function getRouter(): RouterInterface
    {
        return $this->getProvidedDependency(SecurityBlockerPageDependencyProvider::ROUTER);
    }
}
