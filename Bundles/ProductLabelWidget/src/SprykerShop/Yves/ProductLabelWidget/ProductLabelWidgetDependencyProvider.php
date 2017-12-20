<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductLabelWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ProductLabelWidget\Dependency\Client\ProductLabelWidgetToProductLabelStorageClientBridge;

class ProductLabelWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    const CLIENT_PRODUCT_LABEL_STORAGE = 'CLIENT_PRODUCT_LABEL_STORAGE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addProductLabelStorageClient($container);

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function addProductLabelStorageClient(Container $container): Container
    {
        $container[self::CLIENT_PRODUCT_LABEL_STORAGE] = function (Container $container) {
            return new ProductLabelWidgetToProductLabelStorageClientBridge($container->getLocator()->productLabelStorage()->client());
        };

        return $container;
    }
}
