<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationShoppingListWidget\Form\DataProvider;

use Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use SprykerShop\Yves\ProductConfigurationShoppingListWidget\ProductConfigurationShoppingListWidgetConfig;

class ProductConfiguratorButtonFormDataProvider
{
    /**
     * @var \SprykerShop\Yves\ProductConfigurationShoppingListWidget\ProductConfigurationShoppingListWidgetConfig
     */
    protected ProductConfigurationShoppingListWidgetConfig $productConfigurationShoppingListWidgetConfig;

    /**
     * @param \SprykerShop\Yves\ProductConfigurationShoppingListWidget\ProductConfigurationShoppingListWidgetConfig $productConfigurationShoppingListWidgetConfig
     */
    public function __construct(ProductConfigurationShoppingListWidgetConfig $productConfigurationShoppingListWidgetConfig)
    {
        $this->productConfigurationShoppingListWidgetConfig = $productConfigurationShoppingListWidgetConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer
     */
    public function getData(ShoppingListItemTransfer $shoppingListItemTransfer): ProductConfiguratorRequestDataTransfer
    {
        return (new ProductConfiguratorRequestDataTransfer())
            ->setShoppingListItemUuid($shoppingListItemTransfer->getUuidOrFail())
            ->setQuantity($shoppingListItemTransfer->getQuantityOrFail())
            ->setConfiguratorKey($shoppingListItemTransfer->getProductConfigurationInstanceOrFail()->getConfiguratorKeyOrFail())
            ->setSourceType($this->productConfigurationShoppingListWidgetConfig->getShoppingListSourceType());
    }
}
