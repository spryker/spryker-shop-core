<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfiguratorGatewayPage\Validator;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Shared\Log\LoggerTrait;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Dependency\Client\ProductConfiguratorGatewayPageToProductConfigurationClientInterface;

class ProductConfiguratorResponseValidator implements ProductConfiguratorResponseValidatorInterface
{
    use LoggerTrait;

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_RESPONSE_VALIDATION_ERROR = 'product_configuration.response.validation.error';

    /**
     * @var \SprykerShop\Yves\ProductConfiguratorGatewayPage\Dependency\Client\ProductConfiguratorGatewayPageToProductConfigurationClientInterface
     */
    protected $productConfigurationClient;

    /**
     * @param \SprykerShop\Yves\ProductConfiguratorGatewayPage\Dependency\Client\ProductConfiguratorGatewayPageToProductConfigurationClientInterface $productConfigurationClient
     */
    public function __construct(
        ProductConfiguratorGatewayPageToProductConfigurationClientInterface $productConfigurationClient
    ) {
        $this->productConfigurationClient = $productConfigurationClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer
     * @param array<string, mixed> $configuratorResponseData
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer
     */
    public function validateProductConfiguratorCheckSumResponse(
        ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer,
        array $configuratorResponseData
    ): ProductConfiguratorResponseProcessorResponseTransfer {
        $productConfiguratorResponseProcessorResponseTransfer = $this->productConfigurationClient->validateProductConfiguratorCheckSumResponse(
            $productConfiguratorResponseProcessorResponseTransfer,
            $configuratorResponseData,
        );

        if (!$productConfiguratorResponseProcessorResponseTransfer->getIsSuccessful()) {
            return $productConfiguratorResponseProcessorResponseTransfer;
        }

        return $this->validateMandatoryFields($productConfiguratorResponseProcessorResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer
     */
    protected function validateMandatoryFields(
        ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer
    ): ProductConfiguratorResponseProcessorResponseTransfer {
        $productConfiguratorResponseTransfer = $productConfiguratorResponseProcessorResponseTransfer
            ->getProductConfiguratorResponseOrFail();

        try {
            $productConfiguratorResponseTransfer->requireSku();
        } catch (RequiredTransferPropertyException $requiredTransferPropertyException) {
            $this->getLogger()->error(
                static::GLOSSARY_KEY_RESPONSE_VALIDATION_ERROR,
                ['exception' => $requiredTransferPropertyException],
            );

            return $this->addErrorToResponse(
                $productConfiguratorResponseProcessorResponseTransfer,
                static::GLOSSARY_KEY_RESPONSE_VALIDATION_ERROR,
            );
        }

        return $productConfiguratorResponseProcessorResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer
     * @param string $errorMessage
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer
     */
    protected function addErrorToResponse(
        ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer,
        string $errorMessage
    ): ProductConfiguratorResponseProcessorResponseTransfer {
        return $productConfiguratorResponseProcessorResponseTransfer
            ->addMessage((new MessageTransfer())->setValue($errorMessage))
            ->setIsSuccessful(false);
    }
}
