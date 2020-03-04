<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Reader;

use ArrayObject;
use Generated\Shared\Transfer\OrderListTransfer;
use Symfony\Component\HttpFoundation\Request;

interface OrderReaderInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \ArrayObject|\Generated\Shared\Transfer\FilterFieldTransfer[] $filterFieldTransfers
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getOrderList(Request $request, ArrayObject $filterFieldTransfers): OrderListTransfer;
}
