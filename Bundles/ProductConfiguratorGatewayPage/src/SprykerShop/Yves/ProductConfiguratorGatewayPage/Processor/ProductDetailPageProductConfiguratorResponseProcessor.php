<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfiguratorGatewayPage\Processor;

use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Dependency\Client\ProductConfiguratorGatewayPageToProductConfigurationClientInterface;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Dependency\Client\ProductConfiguratorGatewayPageToProductConfigurationStorageClientInterface;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Resolver\ProductDetailPageBackUrlResolverInterface;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Validator\ProductConfiguratorResponseValidatorInterface;

class ProductDetailPageProductConfiguratorResponseProcessor implements ProductDetailPageProductConfiguratorResponseProcessorInerface
{
    /**
     * @var \SprykerShop\Yves\ProductConfiguratorGatewayPage\Dependency\Client\ProductConfiguratorGatewayPageToProductConfigurationClientInterface
     */
    protected $productConfigurationClient;

    /**
     * @var \SprykerShop\Yves\ProductConfiguratorGatewayPage\Dependency\Client\ProductConfiguratorGatewayPageToProductConfigurationStorageClientInterface
     */
    protected $productConfigurationStorageClient;

    /**
     * @var \SprykerShop\Yves\ProductConfiguratorGatewayPage\Validator\ProductConfiguratorResponseValidatorInterface
     */
    protected $productConfiguratorResponseValidator;

    /**
     * @var \SprykerShop\Yves\ProductConfiguratorGatewayPage\Resolver\ProductDetailPageBackUrlResolverInterface
     */
    protected $productDetailPageBackUrlResolver;

    /**
     * @param \SprykerShop\Yves\ProductConfiguratorGatewayPage\Dependency\Client\ProductConfiguratorGatewayPageToProductConfigurationClientInterface $productConfigurationClient
     * @param \SprykerShop\Yves\ProductConfiguratorGatewayPage\Dependency\Client\ProductConfiguratorGatewayPageToProductConfigurationStorageClientInterface $productConfigurationStorageClient
     * @param \SprykerShop\Yves\ProductConfiguratorGatewayPage\Validator\ProductConfiguratorResponseValidatorInterface $productConfiguratorResponseValidator
     * @param \SprykerShop\Yves\ProductConfiguratorGatewayPage\Resolver\ProductDetailPageBackUrlResolverInterface $productDetailPageBackUrlResolver
     */
    public function __construct(
        ProductConfiguratorGatewayPageToProductConfigurationClientInterface $productConfigurationClient,
        ProductConfiguratorGatewayPageToProductConfigurationStorageClientInterface $productConfigurationStorageClient,
        ProductConfiguratorResponseValidatorInterface $productConfiguratorResponseValidator,
        ProductDetailPageBackUrlResolverInterface $productDetailPageBackUrlResolver
    ) {
        $this->productConfigurationClient = $productConfigurationClient;
        $this->productConfigurationStorageClient = $productConfigurationStorageClient;
        $this->productConfiguratorResponseValidator = $productConfiguratorResponseValidator;
        $this->productDetailPageBackUrlResolver = $productDetailPageBackUrlResolver;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     * @param array<string, mixed> $configuratorResponseData
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer
     */
    public function processProductDetailPageProductConfiguratorResponse(
        ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer,
        array $configuratorResponseData
    ): ProductConfiguratorResponseProcessorResponseTransfer {
        $productConfiguratorResponseTransfer = $this->productConfigurationClient->mapProductConfiguratorCheckSumResponse(
            $configuratorResponseData,
            $productConfiguratorResponseTransfer,
        );

        $productConfiguratorResponseProcessorResponseTransfer = (new ProductConfiguratorResponseProcessorResponseTransfer())
            ->setIsSuccessful(true)
            ->setProductConfiguratorResponse($productConfiguratorResponseTransfer);

        $productConfiguratorResponseProcessorResponseTransfer = $this->productConfiguratorResponseValidator->validateProductConfiguratorCheckSumResponse(
            $productConfiguratorResponseProcessorResponseTransfer,
            $configuratorResponseData,
        );

        if (!$productConfiguratorResponseProcessorResponseTransfer->getIsSuccessful()) {
            return $productConfiguratorResponseProcessorResponseTransfer;
        }

        $productConfiguratorResponseTransfer = $productConfiguratorResponseProcessorResponseTransfer->getProductConfiguratorResponseOrFail();

        $this->productConfigurationStorageClient->storeProductConfigurationInstanceBySku(
            $productConfiguratorResponseTransfer->getSkuOrFail(),
            $productConfiguratorResponseTransfer->getProductConfigurationInstanceOrFail(),
        );

        return $productConfiguratorResponseProcessorResponseTransfer->setBackUrl(
            $this->productDetailPageBackUrlResolver->resolveBackUrl($productConfiguratorResponseProcessorResponseTransfer->getProductConfiguratorResponseOrFail()),
        );
    }
}
