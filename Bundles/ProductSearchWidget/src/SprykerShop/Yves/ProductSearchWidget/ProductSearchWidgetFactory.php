<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSearchWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductSearchWidget\Dependency\Client\ProductSearchWidgetToCatalogClientInterface;

class ProductSearchWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ProductSearchWidget\Dependency\Client\ProductSearchWidgetToCatalogClientInterface
     */
    public function getCatalogClient(): ProductSearchWidgetToCatalogClientInterface
    {
        return $this->getProvidedDependency(ProductSearchWidgetDependencyProvider::CLIENT_CATALOG);
    }
}
