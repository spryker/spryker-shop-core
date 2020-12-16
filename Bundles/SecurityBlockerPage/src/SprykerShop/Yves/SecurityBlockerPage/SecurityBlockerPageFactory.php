<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SecurityBlockerPage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\SecurityBlockerPage\Dependency\Client\SecurityBlockerPageToSecurityBlockerClientInterface;
use SprykerShop\Yves\SecurityBlockerPage\EventSubscriber\SecurityBlockerAgentEventSubscriber;
use SprykerShop\Yves\SecurityBlockerPage\EventSubscriber\SecurityBlockerCustomerEventSubscriber;
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
    public function createSecurityBlockerCustomerEventSubscriber(): EventSubscriberInterface
    {
        return new SecurityBlockerCustomerEventSubscriber(
            $this->getRequestStack(),
            $this->getSecurityBlockerClient()
        );
    }

    /**
     * @return \Symfony\Component\EventDispatcher\EventSubscriberInterface
     */
    public function createSecurityBlockerAgentEventSubscriber(): EventSubscriberInterface
    {
        return new SecurityBlockerAgentEventSubscriber(
            $this->getRequestStack(),
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
}
