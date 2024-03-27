<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationshipPage\Reader;

interface MerchantSearchReaderInterface
{
    /**
     * @return array<string, \Generated\Shared\Transfer\MerchantSearchTransfer>
     */
    public function getMerchantSearchTransferIndexedByMerchantReference(): array;
}
