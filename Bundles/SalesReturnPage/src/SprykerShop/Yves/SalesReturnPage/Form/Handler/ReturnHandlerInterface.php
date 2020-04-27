<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesReturnPage\Form\Handler;

use Generated\Shared\Transfer\ReturnResponseTransfer;

interface ReturnHandlerInterface
{
    /**
     * @param array $returnItemsList
     *
     * @return \Generated\Shared\Transfer\ReturnResponseTransfer
     */
    public function createReturn(array $returnItemsList): ReturnResponseTransfer;
}
