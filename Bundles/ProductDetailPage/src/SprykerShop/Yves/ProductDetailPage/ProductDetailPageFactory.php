<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductDetailPage;

use Spryker\Yves\Kernel\AbstractFactory;

class ProductDetailPageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface[]
     */
    public function getProductDetailPageWidgetPlugins()
    {
        return $this->getProvidedDependency(ProductDetailPageDependencyProvider::PLUGIN_PRODUCT_DETAIL_PAGE_WIDGETS);
    }

    /**
     * @return \Spryker\Client\Product\ProductClientInterface
     */
    public function getProductClient()
    {
        return $this->getProvidedDependency(ProductDetailPageDependencyProvider::CLIENT_PRODUCT);
    }
}
