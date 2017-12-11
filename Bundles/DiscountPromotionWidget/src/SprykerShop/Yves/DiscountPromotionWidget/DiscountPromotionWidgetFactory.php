<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\DiscountPromotionWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\DiscountPromotionWidget\Dependency\Client\DiscountPromotionWidgetToProductClientInterface;

class DiscountPromotionWidgetFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Yves\DiscountPromotion\Dependency\PromotionProductMapperPluginInterface
     */
    public function getProductPromotionMapperPlugin()
    {
        return $this->getProvidedDependency(DiscountPromotionWidgetDependencyProvider::PLUGIN_PROMOTION_PRODUCT_MAPPER);
    }

    /**
     * @return \SprykerShop\Yves\DiscountPromotionWidget\Dependency\Client\DiscountPromotionWidgetToProductClientInterface
     */
    public function getProductClient(): DiscountPromotionWidgetToProductClientInterface
    {
        return $this->getProvidedDependency(DiscountPromotionWidgetDependencyProvider::CLIENT_PRODUCT);
    }
}
