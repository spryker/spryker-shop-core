<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\DiscountPromotionWidget;

use Spryker\Yves\DiscountPromotion\Plugin\ProductPromotionMapperPlugin;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ProductDetailPage\Plugin\StorageProductMapperPlugin;

class DiscountPromotionWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    const PLUGIN_PROMOTION_PRODUCT_MAPPER = 'PLUGIN_PROMOTION_PRODUCT_MAPPER';
    const PLUGIN_STORAGE_PRODUCT_MAPPER = 'PLUGIN_STORAGE_PRODUCT_MAPPER';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addPromotionProductMapperPlugin($container);
        $container = $this->addStorageProductMapperPlugin($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addPromotionProductMapperPlugin(Container $container)
    {
        $container[self::PLUGIN_PROMOTION_PRODUCT_MAPPER] = function () {
            return new ProductPromotionMapperPlugin();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addStorageProductMapperPlugin(Container $container)
    {
        $container[self::PLUGIN_STORAGE_PRODUCT_MAPPER] = function () {
            return new StorageProductMapperPlugin();
        };

        return $container;
    }
}
