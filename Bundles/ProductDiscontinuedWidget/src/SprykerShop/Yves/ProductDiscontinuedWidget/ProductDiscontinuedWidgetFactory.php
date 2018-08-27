<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductDiscontinuedWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductDiscontinuedWidget\Dependency\Client\ProductDiscontinuedWidgetToProductDiscontinuedStorageClientInterface;

class ProductDiscontinuedWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ProductDiscontinuedWidget\Dependency\Client\ProductDiscontinuedWidgetToProductDiscontinuedStorageClientInterface
     */
    public function getProductDiscontinuedStorageClient(): ProductDiscontinuedWidgetToProductDiscontinuedStorageClientInterface
    {
        return $this->getProvidedDependency(ProductDiscontinuedWidgetDependencyProvider::CLIENT_PRODUCT_DISCONTINUED_STORAGE);
    }
}
