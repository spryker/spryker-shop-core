<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductBundleWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductBundleWidget\Dependency\Client\ProductBundleWidgetToProductBundleClientInterface;
use SprykerShop\Yves\ProductBundleWidget\ItemFetcher\BundleItemFetcher;
use SprykerShop\Yves\ProductBundleWidget\ItemFetcher\BundleItemFetcherInterface;
use SprykerShop\Yves\ProductBundleWidget\Mapper\BundleItemMapper;
use SprykerShop\Yves\ProductBundleWidget\Mapper\BundleItemMapperInterface;

class ProductBundleWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ProductBundleWidget\ItemFetcher\BundleItemFetcherInterface
     */
    public function createBundleItemFetcher(): BundleItemFetcherInterface
    {
        return new BundleItemFetcher();
    }

    /**
     * @return \SprykerShop\Yves\ProductBundleWidget\Mapper\BundleItemMapperInterface
     */
    public function createBundleItemMapper(): BundleItemMapperInterface
    {
        return new BundleItemMapper();
    }

    /**
     * @return \SprykerShop\Yves\ProductBundleWidget\Dependency\Client\ProductBundleWidgetToProductBundleClientInterface
     */
    public function getProductBundleClient(): ProductBundleWidgetToProductBundleClientInterface
    {
        return $this->getProvidedDependency(ProductBundleWidgetDependencyProvider::CLIENT_PRODUCT_BUNDLE);
    }
}
