<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\WishlistWidget\Dependency\Client;

use Generated\Shared\Transfer\WishlistCollectionTransfer;

interface WishlistWidgetToWishlistClientInterface
{
    /**
     * @return \Generated\Shared\Transfer\WishlistCollectionTransfer
     */
    public function getCustomerWishlistCollection(): WishlistCollectionTransfer;
}
