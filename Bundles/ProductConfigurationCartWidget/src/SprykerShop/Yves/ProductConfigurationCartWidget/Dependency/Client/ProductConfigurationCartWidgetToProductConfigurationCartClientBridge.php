<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationCartWidget\Dependency\Client;

use Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class ProductConfigurationCartWidgetToProductConfigurationCartClientBridge implements ProductConfigurationCartWidgetToProductConfigurationCartClientInterface
{
    /**
     * @var \Spryker\Client\ProductConfigurationCart\ProductConfigurationCartClientInterface
     */
    protected $productConfigurationCartClient;

    /**
     * @param \Spryker\Client\ProductConfigurationCart\ProductConfigurationCartClientInterface $productConfigurationCartClient
     */
    public function __construct($productConfigurationCartClient)
    {
        $this->productConfigurationCartClient = $productConfigurationCartClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteProductConfigurationValid(QuoteTransfer $quoteTransfer): bool
    {
        return $this->productConfigurationCartClient->isQuoteProductConfigurationValid($quoteTransfer);
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
        return $this->productConfigurationCartClient->processProductConfiguratorCheckSumResponse($productConfiguratorResponseTransfer, $configuratorResponseData);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer
     */
    public function resolveProductConfiguratorAccessTokenRedirect(
        ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
    ): ProductConfiguratorRedirectTransfer {
        return $this->productConfigurationCartClient->resolveProductConfiguratorAccessTokenRedirect($productConfiguratorRequestTransfer);
    }
}
