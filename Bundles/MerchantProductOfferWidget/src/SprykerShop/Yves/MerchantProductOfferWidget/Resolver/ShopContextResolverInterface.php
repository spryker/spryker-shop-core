<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidget\Resolver;

use Generated\Shared\Transfer\ShopContextTransfer;

interface ShopContextResolverInterface
{
    /**
     * @return \Generated\Shared\Transfer\ShopContextTransfer
     */
    public function resolve(): ShopContextTransfer;
}
