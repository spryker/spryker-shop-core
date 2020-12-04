<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SecurityBlockerAgentPage;

use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Router\Router\Router;
use Spryker\Yves\Router\Router\RouterInterface;
use SprykerShop\Yves\SecurityBlockerAgentPage\Dependency\Client\SecurityBlockerAgentPageToSecurityBlockerClientInterface;
use SprykerShop\Yves\SecurityBlockerAgentPage\EventSubscriber\FailedLoginMonitoringEventSubscriber;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @method \SprykerShop\Yves\SecurityBlockerAgentPage\SecurityBlockerAgentPageConfig getConfig()
 */
class SecurityBlockerAgentPageFactory extends AbstractFactory
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
     * @return \SprykerShop\Yves\SecurityBlockerAgentPage\Dependency\Client\SecurityBlockerAgentPageToSecurityBlockerClientInterface
     */
    public function getSecurityBlockerClient(): SecurityBlockerAgentPageToSecurityBlockerClientInterface
    {
        return $this->getProvidedDependency(SecurityBlockerAgentPageDependencyProvider::CLIENT_SECURITY_BLOCKER);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RequestStack
     */
    public function getRequestStack(): RequestStack
    {
        return $this->getProvidedDependency(SecurityBlockerAgentPageDependencyProvider::REQUEST_STACK);
    }

    /**
     * @return \Spryker\Yves\Router\Router\RouterInterface
     */
    public function getRouter(): RouterInterface
    {
        return $this->getProvidedDependency(SecurityBlockerAgentPageDependencyProvider::ROUTER);
    }
}
