<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSearchWidget\Builder;

interface MessageBuilderInterface
{
    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function buildErrorMessagesForProductAdditionalData(string $sku): array;
}
