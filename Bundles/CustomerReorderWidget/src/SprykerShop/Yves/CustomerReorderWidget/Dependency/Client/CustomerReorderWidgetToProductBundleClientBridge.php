<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Dependency\Client;

use ArrayObject;

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
     * @param \ArrayObject $items
     * @param \ArrayObject $bundleItems
     *
     * @return array
     */
    public function getGroupedBundleItems(ArrayObject $items, ArrayObject $bundleItems)
    {
        return $this->productBundleClient->getGroupedBundleItems($items, $bundleItems);
    }
}
