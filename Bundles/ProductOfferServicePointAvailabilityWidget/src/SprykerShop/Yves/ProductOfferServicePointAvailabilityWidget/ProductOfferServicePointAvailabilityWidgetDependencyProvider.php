<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Dependency\Client\ProductOfferServicePointAvailabilityWidgetToCartClientBridge;
use SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Dependency\Client\ProductOfferServicePointAvailabilityWidgetToProductOfferServicePointAvailabilityCalculatorStorageClientBridge;

class ProductOfferServicePointAvailabilityWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_CALCULATOR_STORAGE = 'CLIENT_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_CALCULATOR_STORAGE';

    /**
     * @var string
     */
    public const CLIENT_CART = 'CLIENT_CART';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addProductOfferServicePointAvailabilityCalculatorStorageClient($container);
        $container = $this->addCartClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductOfferServicePointAvailabilityCalculatorStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_CALCULATOR_STORAGE, function (Container $container) {
            return new ProductOfferServicePointAvailabilityWidgetToProductOfferServicePointAvailabilityCalculatorStorageClientBridge(
                $container->getLocator()->productOfferServicePointAvailabilityCalculatorStorage()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCartClient(Container $container): Container
    {
        $container->set(static::CLIENT_CART, function (Container $container) {
            return new ProductOfferServicePointAvailabilityWidgetToCartClientBridge(
                $container->getLocator()->cart()->client(),
            );
        });

        return $container;
    }
}
