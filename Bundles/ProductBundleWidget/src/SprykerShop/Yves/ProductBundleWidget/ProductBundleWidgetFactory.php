<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductBundleWidget;

use Spryker\Yves\Kernel\AbstractFactory;

class ProductBundleWidgetFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductBundle\ProductBundleClientInterface
     */
    public function getProductBundleClient()
    {
        return $this->getProvidedDependency(ProductBundleWidgetDependencyProvider::CLIENT_PRODUCT_BUNDLE);
    }
}
