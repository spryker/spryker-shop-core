<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductWidget\Dependency\Client;

interface MerchantProductWidgetToMerchantProductStorageClientInterface
{
    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\MerchantProductTransfer
     */
    public function findOne(string $sku): array;
}
