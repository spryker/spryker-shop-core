<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SecurityBlockerPage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\SecurityBlockerPage\Builder\MessageBuilder;
use SprykerShop\Yves\SecurityBlockerPage\Builder\MessageBuilderInterface;
use SprykerShop\Yves\SecurityBlockerPage\Dependency\Client\SecurityBlockerPageToGlossaryStorageClientInterface;
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
            $this->getSecurityBlockerClient(),
            $this->createMessageBuilder(),
            $this->getLocale()
        );
    }

    /**
     * @return \Symfony\Component\EventDispatcher\EventSubscriberInterface
     */
    public function createSecurityBlockerAgentEventSubscriber(): EventSubscriberInterface
    {
        return new SecurityBlockerAgentEventSubscriber(
            $this->getRequestStack(),
            $this->getSecurityBlockerClient(),
            $this->createMessageBuilder(),
            $this->getLocale()
        );
    }

    /**
     * @return \SprykerShop\Yves\SecurityBlockerPage\Builder\MessageBuilderInterface
     */
    public function createMessageBuilder(): MessageBuilderInterface
    {
        return new MessageBuilder($this->getGlossaryStorageClient());
    }

    /**
     * @return \SprykerShop\Yves\SecurityBlockerPage\Dependency\Client\SecurityBlockerPageToSecurityBlockerClientInterface
     */
    public function getSecurityBlockerClient(): SecurityBlockerPageToSecurityBlockerClientInterface
    {
        return $this->getProvidedDependency(SecurityBlockerPageDependencyProvider::CLIENT_SECURITY_BLOCKER);
    }

    /**
     * @return \SprykerShop\Yves\SecurityBlockerPage\Dependency\Client\SecurityBlockerPageToGlossaryStorageClientInterface
     */
    public function getGlossaryStorageClient(): SecurityBlockerPageToGlossaryStorageClientInterface
    {
        return $this->getProvidedDependency(SecurityBlockerPageDependencyProvider::CLIENT_GLOSSARY_STORAGE);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RequestStack
     */
    public function getRequestStack(): RequestStack
    {
        return $this->getProvidedDependency(SecurityBlockerPageDependencyProvider::SERVICE_REQUEST_STACK);
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->getProvidedDependency(SecurityBlockerPageDependencyProvider::SERVICE_LOCALE);
    }
}
