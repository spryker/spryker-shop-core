<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\StoreWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\StoreWidget\Dependency\Client\StoreWidgetToSessionClientInterface;
use SprykerShop\Yves\StoreWidget\Dependency\Client\StoreWidgetToStoreClientInterface;
use SprykerShop\Yves\StoreWidget\Dependency\Client\StoreWidgetToStoreStorageClientInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @method \SprykerShop\Yves\StoreWidget\StoreWidgetConfig getConfig()
 */
class StoreWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\StoreWidget\Dependency\Client\StoreWidgetToStoreClientInterface
     */
    public function getStoreClient(): StoreWidgetToStoreClientInterface
    {
        return $this->getProvidedDependency(StoreWidgetDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return \SprykerShop\Yves\StoreWidget\Dependency\Client\StoreWidgetToStoreStorageClientInterface
     */
    public function getStoreStorageClient(): StoreWidgetToStoreStorageClientInterface
    {
        return $this->getProvidedDependency(StoreWidgetDependencyProvider::CLIENT_STORE_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\StoreWidget\Dependency\Client\StoreWidgetToSessionClientInterface
     */
    public function getSessionClient(): StoreWidgetToSessionClientInterface
    {
        return $this->getProvidedDependency(StoreWidgetDependencyProvider::CLIENT_SESSION);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request|null
     */
    public function getRequest(): ?Request
    {
        return $this->getRequestStack()->getCurrentRequest();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RequestStack
     */
    public function getRequestStack(): RequestStack
    {
        return $this->getProvidedDependency(StoreWidgetDependencyProvider::SERVICE_REQUEST_STACK);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RequestStack
     */
    public function createRequestStack(): RequestStack
    {
        return new RequestStack();
    }
}
