<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfiguratorGatewayPage\Resolver;

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
        $productConfigurationInstanceTransfer = $this->findProductConfigurationInstance($productConfiguratorRequestDataTransfer);

        $productConfiguratorRequestDataTransfer = $this->configuratorRequestDataMapper
            ->mapProductConfigurationInstanceTransferToProductConfiguratorRequestDataTransfer(
                $productConfiguratorRequestDataTransfer,
                $productConfigurationInstanceTransfer
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
     * @return mixed
     */
    protected function findProductConfigurationInstance(
        ProductConfiguratorRequestDataTransfer $productConfiguratorRequestDataTransfer
    ) {
        $sku = $productConfiguratorRequestDataTransfer->getSku();
        $itemGroupKey = $productConfiguratorRequestDataTransfer->getItemGroupKey();

        if ($sku) {
            $productConfigurationInstanceTransfer = $this->productConfigurationStorageClient
                ->findProductConfigurationInstanceBySku($sku);
        }

        if ($itemGroupKey) {
            $quoteTransfer = $this->quoteClient->getQuote();

            $productConfigurationInstanceTransfer = $this->productConfigurationStorageClient
                ->findProductConfigurationInstanceByGroupKey($itemGroupKey, $quoteTransfer);
        }

        if (!isset($productConfigurationInstanceTransfer)) {
            throw new ProductConfigurationInstanceNotFoundException();
        }

        return $productConfigurationInstanceTransfer;
    }
}
