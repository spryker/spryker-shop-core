<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\DiscountPromotionWidget;

use Spryker\Yves\DiscountPromotion\Plugin\ProductPromotionMapperPlugin;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\DiscountPromotionWidget\Plugin\StorageProductMapperPlugin;

class DiscountPromotionWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    const PLUGIN_PROMOTION_PRODUCT_MAPPER = 'PLUGIN_PROMOTION_PRODUCT_MAPPER';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addPromotionProductMapperPlugin($container);

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
}
