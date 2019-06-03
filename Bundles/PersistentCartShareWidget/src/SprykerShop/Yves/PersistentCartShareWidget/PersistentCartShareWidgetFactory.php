<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PersistentCartShareWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Kernel\Application;
use SprykerShop\Yves\PersistentCartShareWidget\Dependency\Client\PersistentCartShareWidgetToCustomerClientInterface;
use SprykerShop\Yves\PersistentCartShareWidget\Dependency\Client\PersistentCartShareWidgetToPersistentCartShareClientInterface;
use SprykerShop\Yves\PersistentCartShareWidget\PersistentCartShare\PersistentCartShareLinkGenerator;
use SprykerShop\Yves\PersistentCartShareWidget\PersistentCartShare\PersistentCartShareLinkGeneratorInterface;
use SprykerShop\Yves\PersistentCartShareWidget\ResourceShare\ResourceShareRequestBuilder;

class PersistentCartShareWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\PersistentCartShareWidget\Dependency\Client\PersistentCartShareWidgetToCustomerClientInterface
     */
    public function getCustomerClient(): PersistentCartShareWidgetToCustomerClientInterface
    {
        return $this->getProvidedDependency(PersistentCartShareWidgetDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \SprykerShop\Yves\PersistentCartShareWidget\Dependency\Client\PersistentCartShareWidgetToPersistentCartShareClientInterface
     */
    public function getPersistentCartShareClient(): PersistentCartShareWidgetToPersistentCartShareClientInterface
    {
        return $this->getProvidedDependency(PersistentCartShareWidgetDependencyProvider::CLIENT_PERSISTENT_CART_SHARE);
    }

    /**
     * @return \Spryker\Yves\Kernel\Application
     */
    public function getApplication(): Application
    {
        return $this->getProvidedDependency(PersistentCartShareWidgetDependencyProvider::PLUGIN_APPLICATION);
    }

    /**
     * @return \SprykerShop\Yves\PersistentCartShareWidget\ResourceShare\ResourceShareRequestBuilder
     */
    public function createResourceShareRequestBuilder(): ResourceShareRequestBuilder
    {
        return new ResourceShareRequestBuilder(
            $this->getCustomerClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\PersistentCartShareWidget\PersistentCartShare\PersistentCartShareLinkGeneratorInterface
     */
    public function createPersistentCartShareLinkGenerator(): PersistentCartShareLinkGeneratorInterface
    {
        return new PersistentCartShareLinkGenerator(
            $this->getPersistentCartShareClient(),
            $this->getApplication(),
            $this->createResourceShareRequestBuilder(),
            $this->getCustomerClient()
        );
    }
}
