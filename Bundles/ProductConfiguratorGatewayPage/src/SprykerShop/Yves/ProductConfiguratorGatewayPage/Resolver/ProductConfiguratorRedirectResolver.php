<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfiguratorGatewayPage\Resolver;

use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestTransfer;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Dependency\Client\ProductConfiguratorGatewayPageToProductConfigurationClientInterface;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Dependency\Client\ProductConfiguratorGatewayPageToProductConfigurationStorageClientInterface;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Dependency\Client\ProductConfiguratorGatewayPageToQuoteClientInterface;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Exception\ProductConfigurationInstanceNotFoundException;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Mapper\ProductConfiguratorRequestDataMapperInterface;

class ProductConfiguratorRedirectResolver implements ProductConfiguratorRedirectResolverInterface
{
    /**
     * @var \SprykerShop\Yves\ProductConfiguratorGatewayPage\Mapper\ProductConfiguratorRequestDataMapperInterface
     */
    protected $configuratorRequestDataMapper;

    /**
     * @var \SprykerShop\Yves\ProductConfiguratorGatewayPage\Dependency\Client\ProductConfiguratorGatewayPageToProductConfigurationClientInterface
     */
    protected $productConfigurationClient;

    /**
     * @var \SprykerShop\Yves\ProductConfiguratorGatewayPage\Dependency\Client\ProductConfiguratorGatewayPageToProductConfigurationStorageClientInterface
     */
    protected $productConfigurationStorageClient;

    /**
     * @var \SprykerShop\Yves\ProductConfiguratorGatewayPage\Dependency\Client\ProductConfiguratorGatewayPageToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @param \SprykerShop\Yves\ProductConfiguratorGatewayPage\Mapper\ProductConfiguratorRequestDataMapperInterface $configuratorRequestDataMapper
     * @param \SprykerShop\Yves\ProductConfiguratorGatewayPage\Dependency\Client\ProductConfiguratorGatewayPageToProductConfigurationClientInterface $productConfigurationClient
     * @param \SprykerShop\Yves\ProductConfiguratorGatewayPage\Dependency\Client\ProductConfiguratorGatewayPageToProductConfigurationStorageClientInterface $productConfigurationStorageClient
     * @param \SprykerShop\Yves\ProductConfiguratorGatewayPage\Dependency\Client\ProductConfiguratorGatewayPageToQuoteClientInterface $quoteClient
     */
    public function __construct(
        ProductConfiguratorRequestDataMapperInterface $configuratorRequestDataMapper,
        ProductConfiguratorGatewayPageToProductConfigurationClientInterface $productConfigurationClient,
        ProductConfiguratorGatewayPageToProductConfigurationStorageClientInterface $productConfigurationStorageClient,
        ProductConfiguratorGatewayPageToQuoteClientInterface $quoteClient
    ) {
        $this->configuratorRequestDataMapper = $configuratorRequestDataMapper;
        $this->productConfigurationClient = $productConfigurationClient;
        $this->productConfigurationStorageClient = $productConfigurationStorageClient;
        $this->quoteClient = $quoteClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer $productConfiguratorRequestDataTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer
     */
    public function resolveProductConfiguratorRedirect(
        ProductConfiguratorRequestDataTransfer $productConfiguratorRequestDataTransfer
    ): ProductConfiguratorRedirectTransfer {
        $productConfigurationInstanceTransfer = $this->getProductConfigurationInstance($productConfiguratorRequestDataTransfer);

        $productConfiguratorRequestDataTransfer = $this->configuratorRequestDataMapper
            ->mapProductConfigurationInstanceTransferToProductConfiguratorRequestDataTransfer(
                $productConfigurationInstanceTransfer,
                $productConfiguratorRequestDataTransfer
            );

        return $this->productConfigurationClient->resolveProductConfiguratorRedirect(
            (new ProductConfiguratorRequestTransfer())
                ->setProductConfiguratorRequestData($productConfiguratorRequestDataTransfer)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer $productConfiguratorRequestDataTransfer
     *
     * @throws \SprykerShop\Yves\ProductConfiguratorGatewayPage\Exception\ProductConfigurationInstanceNotFoundException
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer
     */
    protected function getProductConfigurationInstance(
        ProductConfiguratorRequestDataTransfer $productConfiguratorRequestDataTransfer
    ): ProductConfigurationInstanceTransfer {
        $sku = $productConfiguratorRequestDataTransfer->getSkuOrFail();
        $itemGroupKey = $productConfiguratorRequestDataTransfer->getItemGroupKey();
        $quantity = $productConfiguratorRequestDataTransfer->getQuantity() ?? 1;

        $productConfigurationInstanceTransfer = null;

        if ($itemGroupKey) {
            $productConfigurationInstanceTransfer = $this->findProductConfigurationInstanceInQuote($itemGroupKey, $sku);
        }

        if (!$itemGroupKey) {
            $productConfigurationInstanceTransfer = $this->productConfigurationStorageClient->findProductConfigurationInstanceBySku($sku);
        }

        if (!$productConfigurationInstanceTransfer) {
            throw new ProductConfigurationInstanceNotFoundException();
        }

        $productConfigurationInstanceTransfer->setQuantity($quantity);

        return $productConfigurationInstanceTransfer;
    }

    /**
     * @param string $itemGroupKey
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer|null
     */
    protected function findProductConfigurationInstanceInQuote(
        string $itemGroupKey,
        string $sku
    ): ?ProductConfigurationInstanceTransfer {
        $quoteTransfer = $this->quoteClient->getQuote();

        return $this->productConfigurationStorageClient
            ->findProductConfigurationInstanceInQuote($itemGroupKey, $sku, $quoteTransfer);
    }
}
