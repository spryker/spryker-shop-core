<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSetListPage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductSetListPage\Dependency\Client\ProductSetListPageToProductSetPageSearchClientInterface;

class ProductSetListPageFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ProductSetListPage\Dependency\Client\ProductSetListPageToProductSetPageSearchClientInterface
     */
    public function getProductSetPageSearchClient(): ProductSetListPageToProductSetPageSearchClientInterface
    {
        return $this->getProvidedDependency(ProductSetListPageDependencyProvider::CLIENT_PRODUCT_SET_PAGE_SEARCH);
    }

    /**
     * @return string[]
     */
    public function getProductSetListPageWidgets(): array
    {
        return $this->getProvidedDependency(ProductSetListPageDependencyProvider::PLUGIN_PRODUCT_SET_LIST_PAGE_WIDGETS);
    }
}
