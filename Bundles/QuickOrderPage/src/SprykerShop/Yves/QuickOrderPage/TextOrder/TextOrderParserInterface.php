<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\TextOrder;

interface TextOrderParserInterface
{
    /**
     * @param string $textOrder
     *
     * @return \Generated\Shared\Transfer\QuickOrderItemTransfer[]
     */
    public function parse(string $textOrder): array;
}
