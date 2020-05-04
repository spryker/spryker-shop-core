<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductBundleWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductBundleWidget\Dependency\Client\ProductBundleWidgetToProductBundleClientInterface;
use SprykerShop\Yves\ProductBundleWidget\Extractor\ItemExtractor;
use SprykerShop\Yves\ProductBundleWidget\Extractor\ItemExtractorInterface;

class ProductBundleWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ProductBundleWidget\Extractor\ItemExtractorInterface
     */
    public function createItemExtractor(): ItemExtractorInterface
    {
        return new ItemExtractor();
    }

    /**
     * @return \SprykerShop\Yves\ProductBundleWidget\Dependency\Client\ProductBundleWidgetToProductBundleClientInterface
     */
    public function getProductBundleClient(): ProductBundleWidgetToProductBundleClientInterface
    {
        return $this->getProvidedDependency(ProductBundleWidgetDependencyProvider::CLIENT_PRODUCT_BUNDLE);
    }
}
