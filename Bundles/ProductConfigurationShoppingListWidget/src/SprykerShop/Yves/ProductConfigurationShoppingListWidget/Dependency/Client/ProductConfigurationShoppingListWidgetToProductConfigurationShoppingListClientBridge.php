<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationShoppingListWidget\Dependency\Client;

use Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;

class ProductConfigurationShoppingListWidgetToProductConfigurationShoppingListClientBridge implements ProductConfigurationShoppingListWidgetToProductConfigurationShoppingListClientInterface
{
    /**
     * @var \Spryker\Client\ProductConfigurationShoppingList\ProductConfigurationShoppingListClientInterface
     */
    protected $productConfigurationShoppingListClient;

    /**
     * @param \Spryker\Client\ProductConfigurationShoppingList\ProductConfigurationShoppingListClientInterface $productConfigurationShoppingListClient
     */
    public function __construct($productConfigurationShoppingListClient)
    {
        $this->productConfigurationShoppingListClient = $productConfigurationShoppingListClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer
     */
    public function resolveProductConfiguratorAccessTokenRedirect(
        ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
    ): ProductConfiguratorRedirectTransfer {
        return $this->productConfigurationShoppingListClient->resolveProductConfiguratorAccessTokenRedirect($productConfiguratorRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     * @param array<string, mixed> $configuratorResponseData
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer
     */
    public function processProductConfiguratorCheckSumResponse(
        ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer,
        array $configuratorResponseData
    ): ProductConfiguratorResponseProcessorResponseTransfer {
        return $this->productConfigurationShoppingListClient->processProductConfiguratorCheckSumResponse(
            $productConfiguratorResponseTransfer,
            $configuratorResponseData,
        );
    }
}
