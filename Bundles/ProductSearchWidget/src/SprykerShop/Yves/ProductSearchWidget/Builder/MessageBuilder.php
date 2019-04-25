<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSearchWidget\Builder;

use Generated\Shared\Transfer\MessageTransfer;

class MessageBuilder implements MessageBuilderInterface
{
    protected const GLOSSARY_KEY_PRODUCT_IS_NOT_EXIST = 'product-cart.validation.error.concrete-product-exists';
    protected const MESSAGE_PARAM_SKU = 'sku';

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function buildErrorMessagesForProductAdditionalData(string $sku): array
    {
        $messages = [];
        $messageTransfer = new MessageTransfer();
        $messageTransfer->setValue(static::GLOSSARY_KEY_PRODUCT_IS_NOT_EXIST);
        $messageTransfer->setParameters([
            static::MESSAGE_PARAM_SKU => $sku,
        ]);
        $messages[] = $messageTransfer;

        return $messages;
    }
}
