<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Handler;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;

class CheckoutErrorMessageHandler implements CheckoutErrorMessageHandlerInterface
{
    protected const PARAMETER_ERROR_MESSAGE_PRODUCT_SKU = '%sku%';
    protected const PARAMETER_ERROR_MESSAGE_PRODUCT_SKU_PRODUCT_BUNDLE = '%productSku%';
    protected const PARAMETERS_ERROR_MESSAGE_SKUS_PRODUCT_BUNDLE = ['%bundleSku%', '%productSku%'];

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutErrorTransfer[]
     */
    public function getUniqueCheckoutErrorMessages(CheckoutResponseTransfer $checkoutResponseTransfer): array
    {
        $checkoutErrorMessagesHashes = [];
        $uniqueCheckoutErrorMessages = [];
        $processedBundleItemsSkus = [];

        foreach ($checkoutResponseTransfer->getErrors() as $checkoutErrorTransfer) {
            $checkoutErrorMessageHash = $this->getCheckoutErrorMessageHash($checkoutErrorTransfer);

            if (!\in_array($checkoutErrorMessageHash, $checkoutErrorMessagesHashes, true)) {
                if ($this->isErrorRelatedToProductBundle($checkoutErrorTransfer)) {
                    $uniqueCheckoutErrorMessages[] = $checkoutErrorTransfer;
                    $processedBundleItemsSkus[] = $this->getProductBundleSingleItemSku($checkoutErrorTransfer);
                    continue;
                }

                if (!\in_array($this->getProductItemSku($checkoutErrorTransfer), $processedBundleItemsSkus, true)) {
                    $uniqueCheckoutErrorMessages[] = $checkoutErrorTransfer;
                }

                $checkoutErrorMessagesHashes[] = $checkoutErrorMessageHash;
            }
        }

        return $uniqueCheckoutErrorMessages;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutErrorTransfer $checkoutErrorTransfer
     *
     * @return bool
     */
    protected function isErrorRelatedToProductBundle(CheckoutErrorTransfer $checkoutErrorTransfer): bool
    {
        $detailedMessage = $checkoutErrorTransfer->getDetailedMessage();

        if (!$detailedMessage) {
            return false;
        }

        $detailedMessageParameterKeys = array_keys($detailedMessage->getParameters());
        sort($detailedMessageParameterKeys);

        return $detailedMessageParameterKeys === static::PARAMETERS_ERROR_MESSAGE_SKUS_PRODUCT_BUNDLE;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutErrorTransfer $checkoutErrorTransfer
     *
     * @return string
     */
    protected function getProductItemSku(CheckoutErrorTransfer $checkoutErrorTransfer): string
    {
        $checkoutErrorTransfer->requireDetailedMessage();
        $detailedMessage = $checkoutErrorTransfer->getDetailedMessage();

        $detailedMessage->requireParameters();
        $detailedMessageParameters = $detailedMessage->getParameters();

        return $detailedMessageParameters[static::PARAMETER_ERROR_MESSAGE_PRODUCT_SKU] ?? '';
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutErrorTransfer $checkoutErrorTransfer
     *
     * @return string
     */
    protected function getProductBundleSingleItemSku(CheckoutErrorTransfer $checkoutErrorTransfer): string
    {
        $checkoutErrorTransfer->requireDetailedMessage();
        $detailedMessage = $checkoutErrorTransfer->getDetailedMessage();

        $detailedMessage->requireParameters();
        $detailedMessageParameters = $detailedMessage->getParameters();

        return $detailedMessageParameters[static::PARAMETER_ERROR_MESSAGE_PRODUCT_SKU_PRODUCT_BUNDLE] ?? '';
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutErrorTransfer $checkoutErrorTransfer
     *
     * @return string
     */
    protected function getCheckoutErrorMessageHash(CheckoutErrorTransfer $checkoutErrorTransfer): string
    {
        $detailedMessage = $checkoutErrorTransfer->getDetailedMessage();

        if ($detailedMessage) {
            return $this->getCheckoutErrorDetailedMessageHash($detailedMessage);
        }

        return $this->getCheckoutErrorSimpleMessageHash($checkoutErrorTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer $messageTransfer
     *
     * @return string
     */
    protected function getCheckoutErrorDetailedMessageHash(MessageTransfer $messageTransfer): string
    {
        return crc32(
            $messageTransfer->getValue() . implode($messageTransfer->getParameters())
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutErrorTransfer $checkoutErrorTransfer
     *
     * @return string
     */
    protected function getCheckoutErrorSimpleMessageHash(CheckoutErrorTransfer $checkoutErrorTransfer): string
    {
        return crc32(
            $checkoutErrorTransfer->getMessage() . $checkoutErrorTransfer->getErrorCode()
        );
    }
}
