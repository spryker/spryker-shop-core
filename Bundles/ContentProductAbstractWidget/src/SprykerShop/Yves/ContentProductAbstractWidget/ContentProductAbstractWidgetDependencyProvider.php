<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentProductAbstractWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ContentProductAbstractWidget\Dependency\Client\ContentProductAbstractWidgetToContentProductClientBridge;
use SprykerShop\Yves\ContentProductAbstractWidget\Dependency\Client\ContentProductAbstractWidgetToProductStorageClientBridge;

class ContentProductAbstractWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_PRODUCT_STORAGE = 'CLIENT_PRODUCT_STORAGE';
    public const CLIENT_CONTENT_PRODUCT = 'CLIENT_CONTENT_PRODUCT';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addContentProductClient($container);
        $container = $this->addProductStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addContentProductClient(Container $container): Container
    {
        $container[self::CLIENT_CONTENT_PRODUCT] = function (Container $container) {
            return new ContentProductAbstractWidgetToContentProductClientBridge($container->getLocator()->contentProduct()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductStorageClient(Container $container): Container
    {
        $container[self::CLIENT_PRODUCT_STORAGE] = function (Container $container) {
            return new ContentProductAbstractWidgetToProductStorageClientBridge($container->getLocator()->productStorage()->client());
        };

        return $container;
    }
}
