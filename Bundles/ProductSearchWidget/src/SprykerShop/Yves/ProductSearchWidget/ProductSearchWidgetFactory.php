<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSearchWidget;

use Spryker\Yves\Kernel\AbstractFactory;

class ProductSearchWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ProductSearchWidget\Dependency\Client\ProductSearchWidgetToProductPageSearchClientInterface
     */
    public function getProductPageSearchClient()
    {
        return $this->getProvidedDependency(ProductSearchWidgetDependencyProvider::CLIENT_PRODUCT_PAGE_SEARCH);
    }
}
