<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductLabelWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductLabelWidget\Dependency\Client\ProductLabelWidgetToProductLabelStorageClientInterface;
use SprykerShop\Yves\ProductLabelWidget\Dependency\Client\ProductLabelWidgetToStoreClientInterface;

class ProductLabelWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ProductLabelWidget\Dependency\Client\ProductLabelWidgetToProductLabelStorageClientInterface
     */
    public function getProductLabelStorageClient(): ProductLabelWidgetToProductLabelStorageClientInterface
    {
        return $this->getProvidedDependency(ProductLabelWidgetDependencyProvider::CLIENT_PRODUCT_LABEL_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\ProductLabelWidget\Dependency\Client\ProductLabelWidgetToStoreClientInterface
     */
    public function getStoreClient(): ProductLabelWidgetToStoreClientInterface
    {
        return $this->getProvidedDependency(ProductLabelWidgetDependencyProvider::CLIENT_STORE);
    }
}
