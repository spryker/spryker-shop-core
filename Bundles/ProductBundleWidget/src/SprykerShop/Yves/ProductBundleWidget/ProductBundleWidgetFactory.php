<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductBundleWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductBundleWidget\Dependency\Client\ProductBundleWidgetToProductBundleClientInterface;
use SprykerShop\Yves\ProductBundleWidget\Expander\ReturnCreateFormExpander;
use SprykerShop\Yves\ProductBundleWidget\Expander\ReturnCreateFormExpanderInterface;
use SprykerShop\Yves\ProductBundleWidget\Extractor\ItemExtractor;
use SprykerShop\Yves\ProductBundleWidget\Extractor\ItemExtractorInterface;
use SprykerShop\Yves\ProductBundleWidget\Handler\ReturnCreateFormHandler;
use SprykerShop\Yves\ProductBundleWidget\Handler\ReturnCreateFormHandlerInterface;

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
     * @return \SprykerShop\Yves\ProductBundleWidget\Expander\ReturnCreateFormExpanderInterface
     */
    public function createSalesReturnPageFormExpander(): ReturnCreateFormExpanderInterface
    {
        return new ReturnCreateFormExpander();
    }

    /**
     * @return \SprykerShop\Yves\ProductBundleWidget\Handler\ReturnCreateFormHandlerInterface
     */
    public function createReturnCreateFormHandler(): ReturnCreateFormHandlerInterface
    {
        return new ReturnCreateFormHandler();
    }
}
