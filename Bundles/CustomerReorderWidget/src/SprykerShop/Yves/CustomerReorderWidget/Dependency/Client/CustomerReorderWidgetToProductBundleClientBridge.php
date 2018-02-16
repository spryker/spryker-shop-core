<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Dependency\Client;

use ArrayObject;
use Generated\Shared\Transfer\QuoteTransfer;

class CustomerReorderWidgetToProductBundleClientBridge implements CustomerReorderWidgetToProductBundleClientInterface
{
    /**
     * @var \Spryker\Client\ProductBundle\ProductBundleClientInterface
     */
    protected $productBundleClient;

    /**
     * @param \Spryker\Client\ProductBundle\ProductBundleClientInterface $productBundleClient
     */
    public function __construct($productBundleClient)
    {
        $this->productBundleClient = $productBundleClient;
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function getItemsWithBundlesItems(QuoteTransfer $quoteTransfer): array
    {
        return $this->productBundleClient->getItemsWithBundlesItems($quoteTransfer);
    }
}
