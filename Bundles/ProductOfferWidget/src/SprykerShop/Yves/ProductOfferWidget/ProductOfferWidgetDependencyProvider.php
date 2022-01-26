<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductOfferWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ProductOfferWidget\Dependency\Client\ProductOfferWidgetToMerchantProductOfferStorageClientBridge;

/**
 * @method \SprykerShop\Yves\ProductOfferWidget\ProductOfferWidgetConfig getConfig()
 */
class ProductOfferWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_MERCHANT_PRODUCT_OFFER_STORAGE = 'CLIENT_MERCHANT_PRODUCT_OFFER_STORAGE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        parent::provideDependencies($container);

        $container = $this->addMerchantProductOfferStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addMerchantProductOfferStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_MERCHANT_PRODUCT_OFFER_STORAGE, function (Container $container) {
            return new ProductOfferWidgetToMerchantProductOfferStorageClientBridge(
                $container->getLocator()->merchantProductOfferStorage()->client(),
            );
        });

        return $container;
    }
}
