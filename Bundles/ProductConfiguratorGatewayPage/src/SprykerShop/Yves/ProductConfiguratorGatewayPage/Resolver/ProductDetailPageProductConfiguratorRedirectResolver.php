<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfiguratorGatewayPage\Resolver;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer;
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
     * @var \SprykerShop\Yves\ProductConfiguratorGatewayPage\Dependency\Client\ProductConfiguratorGatewayPageToProductConfigurationClientInterface
     */
    protected $productConfigurationClient;

    /**
     * @var \SprykerShop\Yves\ProductConfiguratorGatewayPage\Dependency\Client\ProductConfiguratorGatewayPageToProductConfigurationStorageClientInterface
     */
    protected $productConfigurationStorageClient;

    /**
     * @var \SprykerShop\Yves\ProductConfiguratorGatewayPage\Mapper\ProductConfiguratorRequestMapperInterface
     */
    protected $productConfiguratorRequestMapper;

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
        $productConfiguratorRedirectTransfer = new ProductConfiguratorRedirectTransfer();

        $productConfigurationInstanceTransfer = $this->productConfigurationStorageClient->findProductConfigurationInstanceBySku(
            $productConfiguratorRequestTransfer->getProductConfiguratorRequestDataOrFail()->getSkuOrFail(),
        );

        if (!$productConfigurationInstanceTransfer) {
            return $this->addErrorToProductConfiguratorRedirect(
                $productConfiguratorRedirectTransfer,
                static::GLOSSARY_KEY_PRODUCT_CONFIGURATION_NOT_FOUND,
                [static::GLOSSARY_KEY_PARAM_SKU => $productConfiguratorRequestTransfer->getProductConfiguratorRequestDataOrFail()->getSkuOrFail()],
            );
        }

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
}
