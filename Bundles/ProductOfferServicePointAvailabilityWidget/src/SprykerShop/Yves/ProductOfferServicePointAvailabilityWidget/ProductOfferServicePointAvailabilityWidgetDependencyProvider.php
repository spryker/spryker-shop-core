<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Dependency\Client\ProductOfferServicePointAvailabilityWidgetToCartClientBridge;
use SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Dependency\Client\ProductOfferServicePointAvailabilityWidgetToProductOfferServicePointAvailabilityCalculatorClientBridge;

class ProductOfferServicePointAvailabilityWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_CALCULATOR = 'CLIENT_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_CALCULATOR';

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

        $container = $this->addProductOfferServicePointAvailabilityCalculatorClient($container);
        $container = $this->addCartClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductOfferServicePointAvailabilityCalculatorClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_CALCULATOR, function (Container $container) {
            return new ProductOfferServicePointAvailabilityWidgetToProductOfferServicePointAvailabilityCalculatorClientBridge(
                $container->getLocator()->productOfferServicePointAvailabilityCalculator()->client(),
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
