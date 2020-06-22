<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductWidget\Dependency\Client;

use Generated\Shared\Transfer\MerchantProductStorageTransfer;

interface MerchantProductWidgetToMerchantProductStorageClientInterface
{
    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\MerchantProductStorageTransfer|null
     */
    public function findOne(int $idProductAbstract): ?MerchantProductStorageTransfer;
}
