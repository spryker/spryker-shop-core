<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client;

use Generated\Shared\Transfer\ProductOfferViewCollectionTransfer;

interface MerchantProductOfferWidgetToMerchantProductOfferStorageClientInterface
{
    /**
     * @param string $concreteSku
     *
     * @return \Generated\Shared\Transfer\ProductOfferViewCollectionTransfer
     */
    public function getProductOfferViewCollection(string $concreteSku): ProductOfferViewCollectionTransfer;
}
