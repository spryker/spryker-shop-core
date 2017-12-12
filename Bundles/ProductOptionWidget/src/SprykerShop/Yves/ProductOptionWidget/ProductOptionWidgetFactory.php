<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductOptionWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductOptionWidget\Dependency\Client\ProductOptionWidgetToProductOptionClientInterface;

class ProductOptionWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ProductOptionWidget\Dependency\Client\ProductOptionWidgetToProductOptionClientInterface
     */
    public function getProductOptionClient(): ProductOptionWidgetToProductOptionClientInterface
    {
        return $this->getProvidedDependency(ProductOptionWidgetDependencyProvider::CLIENT_PRODUCT_OPTION);
    }
}
