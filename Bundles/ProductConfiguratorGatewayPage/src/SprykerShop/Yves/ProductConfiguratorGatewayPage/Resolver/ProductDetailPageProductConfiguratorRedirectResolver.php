<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfiguratorGatewayPage\Resolver;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceCollectionTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceConditionsTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceCriteriaTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestTransfer;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Dependency\Client\ProductConfiguratorGatewayPageToProductConfigurationClientInterface;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Dependency\Client\ProductConfiguratorGatewayPageToProductConfigurationStorageClientInterface;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Mapper\ProductConfiguratorRequestMapperInterface;

class ProductDetailPageProductConfiguratorRedirectResolver implements ProductDetailPageProductConfiguratorRedirectResolverInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PRODUCT_CONFIGURATION_NOT_FOUND = 'product_configuration.error.configuration_not_found';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PARAM_SKU = '%sku%';

    /**
     * @var int
     */
    protected const PRODUCT_QUANITY = 1;

    /**
     * @var \SprykerShop\Yves\ProductConfiguratorGatewayPage\Dependency\Client\ProductConfiguratorGatewayPageToProductConfigurationClientInterface
     */
    protected ProductConfiguratorGatewayPageToProductConfigurationClientInterface $productConfigurationClient;

    /**
     * @var \SprykerShop\Yves\ProductConfiguratorGatewayPage\Dependency\Client\ProductConfiguratorGatewayPageToProductConfigurationStorageClientInterface
     */
    protected ProductConfiguratorGatewayPageToProductConfigurationStorageClientInterface $productConfigurationStorageClient;

    /**
     * @var \SprykerShop\Yves\ProductConfiguratorGatewayPage\Mapper\ProductConfiguratorRequestMapperInterface
     */
    protected ProductConfiguratorRequestMapperInterface $productConfiguratorRequestMapper;

    /**
     * @param \SprykerShop\Yves\ProductConfiguratorGatewayPage\Dependency\Client\ProductConfiguratorGatewayPageToProductConfigurationClientInterface $productConfigurationClient
     * @param \SprykerShop\Yves\ProductConfiguratorGatewayPage\Dependency\Client\ProductConfiguratorGatewayPageToProductConfigurationStorageClientInterface $productConfigurationStorageClient
     * @param \SprykerShop\Yves\ProductConfiguratorGatewayPage\Mapper\ProductConfiguratorRequestMapperInterface $productConfiguratorRequestMapper
     */
    public function __construct(
        ProductConfiguratorGatewayPageToProductConfigurationClientInterface $productConfigurationClient,
        ProductConfiguratorGatewayPageToProductConfigurationStorageClientInterface $productConfigurationStorageClient,
        ProductConfiguratorRequestMapperInterface $productConfiguratorRequestMapper
    ) {
        $this->productConfigurationClient = $productConfigurationClient;
        $this->productConfigurationStorageClient = $productConfigurationStorageClient;
        $this->productConfiguratorRequestMapper = $productConfiguratorRequestMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer
     */
    public function resolveProductConfiguratorAccessTokenRedirect(
        ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
    ): ProductConfiguratorRedirectTransfer {
        $productConfiguratorRequestDataTransfer = $productConfiguratorRequestTransfer->getProductConfiguratorRequestDataOrFail();
        $productConfigurationInstanceCollectionTransfer = $this->getProductConfigurationInstanceCollection($productConfiguratorRequestDataTransfer);

        if (!$productConfigurationInstanceCollectionTransfer->getProductConfigurationInstances()->count()) {
            return $this->addErrorToProductConfiguratorRedirect(
                new ProductConfiguratorRedirectTransfer(),
                static::GLOSSARY_KEY_PRODUCT_CONFIGURATION_NOT_FOUND,
                [static::GLOSSARY_KEY_PARAM_SKU => $productConfiguratorRequestDataTransfer->getSkuOrFail()],
            );
        }

        $productConfigurationInstanceTransfer = $productConfigurationInstanceCollectionTransfer->getProductConfigurationInstances()
            ->getIterator()
            ->current()
            ->setQuantity(static::PRODUCT_QUANITY);

        $productConfiguratorRequestTransfer = $this->productConfiguratorRequestMapper->mapProductConfigurationInstanceTransferToProductConfiguratorRequestTransfer(
            $productConfigurationInstanceTransfer,
            $productConfiguratorRequestTransfer,
        );

        return $this->productConfigurationClient->sendProductConfiguratorAccessTokenRequest($productConfiguratorRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer $productConfiguratorRedirectTransfer
     * @param string $message
     * @param array<string, mixed> $parameters
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer
     */
    protected function addErrorToProductConfiguratorRedirect(
        ProductConfiguratorRedirectTransfer $productConfiguratorRedirectTransfer,
        string $message,
        array $parameters = []
    ): ProductConfiguratorRedirectTransfer {
        $messageTransfer = (new MessageTransfer())
            ->setValue($message)
            ->setParameters($parameters);

        return $productConfiguratorRedirectTransfer
            ->setIsSuccessful(false)
            ->addMessage($messageTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer $productConfiguratorRequestDataTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceCollectionTransfer
     */
    protected function getProductConfigurationInstanceCollection(
        ProductConfiguratorRequestDataTransfer $productConfiguratorRequestDataTransfer
    ): ProductConfigurationInstanceCollectionTransfer {
        $productConfigurationInstanceConditionsTransfer = (new ProductConfigurationInstanceConditionsTransfer())
            ->addSku($productConfiguratorRequestDataTransfer->getSkuOrFail());

        $productConfigurationInstanceCriteriaTransfer = (new ProductConfigurationInstanceCriteriaTransfer())
            ->setProductConfigurationInstanceConditions($productConfigurationInstanceConditionsTransfer);

        return $this->productConfigurationStorageClient
            ->getProductConfigurationInstanceCollection($productConfigurationInstanceCriteriaTransfer);
    }
}
