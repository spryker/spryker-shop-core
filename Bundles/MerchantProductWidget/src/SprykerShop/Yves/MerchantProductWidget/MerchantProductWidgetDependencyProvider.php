<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class MerchantProductWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_MERCHANT_PRODUCT_STORAGE = 'CLIENT_MERCHANT_PRODUCT_STORAGE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        parent::provideDependencies($container);

        $container = $this->addMerchantProductStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addMerchantProductStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_MERCHANT_PRODUCT_STORAGE, function (Container $container) {
            return new MerchantProductWidgetToMerchantProductStorageClientBridge($container->getLocator()->merchantProductStorage()->client());
        });

        return $container;
    }
}
