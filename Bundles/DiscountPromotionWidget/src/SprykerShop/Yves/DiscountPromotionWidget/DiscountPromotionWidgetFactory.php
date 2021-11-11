<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\DiscountPromotionWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\DiscountPromotionWidget\Dependency\Client\DiscountPromotionWidgetToProductStorageClientInterface;
use SprykerShop\Yves\DiscountPromotionWidget\Dependency\Service\DiscountPromotionWidgetToDiscountServiceInterface;

class DiscountPromotionWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\DiscountPromotionWidget\Dependency\Client\DiscountPromotionWidgetToProductStorageClientInterface
     */
    public function getProductStorageClient(): DiscountPromotionWidgetToProductStorageClientInterface
    {
        return $this->getProvidedDependency(DiscountPromotionWidgetDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\DiscountPromotionWidget\Dependency\Service\DiscountPromotionWidgetToDiscountServiceInterface
     */
    public function getDiscountService(): DiscountPromotionWidgetToDiscountServiceInterface
    {
        return $this->getProvidedDependency(DiscountPromotionWidgetDependencyProvider::SERVICE_DISCOUNT);
    }
}
