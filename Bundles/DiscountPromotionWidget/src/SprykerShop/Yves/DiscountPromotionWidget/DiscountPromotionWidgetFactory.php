<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\DiscountPromotionWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\DiscountPromotionWidget\Dependency\Client\DiscountPromotionWidgetToProductStorageClientInterface;

class DiscountPromotionWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\DiscountPromotionWidget\Dependency\Client\DiscountPromotionWidgetToProductStorageClientInterface
     */
    public function getProductStorageClient(): DiscountPromotionWidgetToProductStorageClientInterface
    {
        return $this->getProvidedDependency(DiscountPromotionWidgetDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }
}
