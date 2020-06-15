<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesReturnPage\Reader;

use Generated\Shared\Transfer\ReturnTransfer;

interface ReturnReaderInterface
{
    /**
     * @param string $returnReference
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\ReturnTransfer|null
     */
    public function findReturnByReference(string $returnReference, string $customerReference): ?ReturnTransfer;
}
