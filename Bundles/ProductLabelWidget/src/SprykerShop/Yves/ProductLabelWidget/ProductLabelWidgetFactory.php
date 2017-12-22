<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductLabelWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductLabelWidget\Dependency\Client\ProductLabelWidgetToProductLabelStorageClientInterface;

class ProductLabelWidgetFactory extends AbstractFactory
{
    /**
     * @return ProductLabelWidgetToProductLabelStorageClientInterface
     */
    public function getProductLabelStorageClient(): ProductLabelWidgetToProductLabelStorageClientInterface
    {
        return $this->getProvidedDependency(ProductLabelWidgetDependencyProvider::CLIENT_PRODUCT_LABEL_STORAGE);
    }
}
