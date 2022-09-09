<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationShoppingListWidget\Checker;

use Generated\Shared\Transfer\ProductConfiguratorRequestTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;
use SprykerShop\Yves\ProductConfigurationShoppingListWidget\ProductConfigurationShoppingListWidgetConfig;

class ShoppingListPageApplicabilityChecker implements ShoppingListPageApplicabilityCheckerInterface
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
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
     *
     * @return bool
     */
    public function isRequestApplicable(ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer): bool
    {
        $isConfiguratorKeySupported = $this->isConfiguratorKeySupported(
            $productConfiguratorRequestTransfer->getProductConfiguratorRequestDataOrFail()->getConfiguratorKeyOrFail(),
        );

        return $isConfiguratorKeySupported && $productConfiguratorRequestTransfer->getProductConfiguratorRequestDataOrFail()->getSourceType()
            === $this->productConfigurationShoppingListWidgetConfig->getShoppingListSourceType();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     *
     * @return bool
     */
    public function isResponseApplicable(ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer): bool
    {
        $isConfiguratorKeySupported = $this->isConfiguratorKeySupported(
            $productConfiguratorResponseTransfer->getProductConfigurationInstanceOrFail()->getConfiguratorKeyOrFail(),
        );

        return $isConfiguratorKeySupported && $productConfiguratorResponseTransfer->getSourceType()
            === $this->productConfigurationShoppingListWidgetConfig->getShoppingListSourceType();
    }

    /**
     * @param string $configuratorKey
     *
     * @return bool
     */
    protected function isConfiguratorKeySupported(string $configuratorKey): bool
    {
        return in_array(
            $configuratorKey,
            $this->productConfigurationShoppingListWidgetConfig->getSupportedConfiguratorKeys(),
            true,
        );
    }
}
