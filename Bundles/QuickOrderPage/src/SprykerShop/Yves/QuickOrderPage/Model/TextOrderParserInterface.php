<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Model;

interface TextOrderParserInterface
{
    /**
     * @return $this
     */
    public function parse(): self;

    /**
     * @return \Generated\Shared\Transfer\QuickOrderItemTransfer[]
     */
    public function getParsedTextOrderItems(): array;

    /**
     * @return array
     */
    public function getNotFoundProducts(): array;
}
