<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductBundleWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductBundleWidget\Dependency\Client\ProductBundleWidgetToProductBundleClientInterface;
use SprykerShop\Yves\ProductBundleWidget\Expander\SalesReturnPageFormExpander;
use SprykerShop\Yves\ProductBundleWidget\Expander\SalesReturnPageFormExpanderInterface;
use SprykerShop\Yves\ProductBundleWidget\Extractor\ItemExtractor;
use SprykerShop\Yves\ProductBundleWidget\Extractor\ItemExtractorInterface;
use SprykerShop\Yves\ProductBundleWidget\Handler\SalesReturnPageFormHandler;
use SprykerShop\Yves\ProductBundleWidget\Handler\SalesReturnPageFormHandlerInterface;

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

    /**
     * @return \SprykerShop\Yves\ProductBundleWidget\Expander\SalesReturnPageFormExpanderInterface
     */
    public function getSalesReturnPageFormExpander(): SalesReturnPageFormExpanderInterface
    {
        return new SalesReturnPageFormExpander();
    }

    /**
     * @return \SprykerShop\Yves\ProductBundleWidget\Handler\SalesReturnPageFormHandlerInterface
     */
    public function getSalesReturnPageFormHandler(): SalesReturnPageFormHandlerInterface
    {
        return new SalesReturnPageFormHandler();
    }
}
