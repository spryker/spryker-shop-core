<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductOptionWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductOptionWidget\Dependency\Client\ProductOptionWidgetToProductOptionStorageClientInterface;
use SprykerShop\Yves\ProductOptionWidget\Form\DataProvider\ShoppingListItemProductOptionFormDataProvider;
use SprykerShop\Yves\ProductOptionWidget\Form\DataProvider\ShoppingListItemProductOptionFormDataProviderInterface;

class ProductOptionWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ProductOptionWidget\Form\DataProvider\ShoppingListItemProductOptionFormDataProviderInterface
     */
    public function createShoppingListItemProductOptionFormDataProvider(): ShoppingListItemProductOptionFormDataProviderInterface
    {
        return new ShoppingListItemProductOptionFormDataProvider(
            $this->getProductOptionStorageClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\ProductOptionWidget\Dependency\Client\ProductOptionWidgetToProductOptionStorageClientInterface
     */
    public function getProductOptionStorageClient(): ProductOptionWidgetToProductOptionStorageClientInterface
    {
        return $this->getProvidedDependency(ProductOptionWidgetDependencyProvider::CLIENT_PRODUCT_OPTION_STORAGE);
    }
}
