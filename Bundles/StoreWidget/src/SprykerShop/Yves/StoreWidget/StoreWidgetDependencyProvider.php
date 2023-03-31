<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\StoreWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\StoreWidget\Dependency\Client\StoreWidgetToSessionClientBridge;
use SprykerShop\Yves\StoreWidget\Dependency\Client\StoreWidgetToStoreClientBridge;
use SprykerShop\Yves\StoreWidget\Dependency\Client\StoreWidgetToStoreStorageClientBridge;

/**
 * @method \SprykerShop\Yves\StoreWidget\StoreWidgetConfig getConfig()
 */
class StoreWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_SESSION = 'CLIENT_SESSION';

    /**
     * @var string
     */
    public const CLIENT_STORE_STORAGE = 'CLIENT_STORE_STORAGE';

    /**
     * @var string
     */
    public const CLIENT_STORE = 'CLIENT_STORE';

    /**
     * @uses \Spryker\Yves\Http\Plugin\Application\HttpApplicationPlugin::SERVICE_REQUEST_STACK
     *
     * @var string
     */
    public const SERVICE_REQUEST_STACK = 'request_stack';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addStoreClient($container);
        $container = $this->addStoreStorageClient($container);
        $container = $this->addSessionClient($container);
        $container = $this->addRequestStack($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addStoreClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORE, function (Container $container) {
            return new StoreWidgetToStoreClientBridge(
                $container->getLocator()->store()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addStoreStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORE_STORAGE, function (Container $container) {
            return new StoreWidgetToStoreStorageClientBridge(
                $container->getLocator()->storeStorage()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSessionClient(Container $container): Container
    {
        $container->set(static::CLIENT_SESSION, function (Container $container) {
            return new StoreWidgetToSessionClientBridge(
                $container->getLocator()->session()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addRequestStack(Container $container): Container
    {
        $container->set(static::SERVICE_REQUEST_STACK, function (Container $container) {
            return $container->getApplicationService(static::SERVICE_REQUEST_STACK);
        });

        return $container;
    }
}
