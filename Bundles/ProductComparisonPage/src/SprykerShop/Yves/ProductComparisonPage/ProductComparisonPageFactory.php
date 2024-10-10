<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductComparisonPage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductComparisonPage\Collector\ProductAttributeCollector;
use SprykerShop\Yves\ProductComparisonPage\Collector\ProductAttributeCollectorInterface;
use SprykerShop\Yves\ProductComparisonPage\Dependency\Client\ProductComparisonPageToProductStorageClientInterface;
use SprykerShop\Yves\ProductComparisonPage\Reader\ProductComparisonListReader;
use SprykerShop\Yves\ProductComparisonPage\Reader\ProductComparisonListReaderInterface;

/**
 * @method \SprykerShop\Yves\ProductComparisonPage\ProductComparisonPageConfig getConfig()
 */
class ProductComparisonPageFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ProductComparisonPage\Reader\ProductComparisonListReaderInterface
     */
    public function createProductComparisonListReader(): ProductComparisonListReaderInterface
    {
        return new ProductComparisonListReader($this->getProductStorageClient());
    }

    /**
     * @return \SprykerShop\Yves\ProductComparisonPage\Collector\ProductAttributeCollectorInterface
     */
    public function createProductAttributeCollector(): ProductAttributeCollectorInterface
    {
        return new ProductAttributeCollector();
    }

    /**
     * @return \SprykerShop\Yves\ProductComparisonPage\Dependency\Client\ProductComparisonPageToProductStorageClientInterface
     */
    public function getProductStorageClient(): ProductComparisonPageToProductStorageClientInterface
    {
        return $this->getProvidedDependency(ProductComparisonPageDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }
}
