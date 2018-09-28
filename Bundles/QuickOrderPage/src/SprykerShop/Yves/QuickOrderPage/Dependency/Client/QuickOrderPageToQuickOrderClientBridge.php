<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Dependency\Client;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\QuickOrderProductPriceTransfer;

class QuickOrderPageToQuickOrderClientBridge implements QuickOrderPageToQuickOrderClientInterface
{
    /**
     * @var \Spryker\Client\QuickOrder\QuickOrderClientInterface
     */
    protected $quickOrderClient;

    /**
     * @param \Spryker\Client\QuickOrder\QuickOrderClientInterface $quickOrderClient
     */
    public function __construct($quickOrderClient)
    {
        $this->quickOrderClient = $quickOrderClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function expandProductConcreteTransfer(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        return $this->quickOrderClient->expandProductConcreteTransfer($productConcreteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderProductPriceTransfer $quickOrderProductPriceTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderProductPriceTransfer
     */
    public function getQuickOrderProductPrice(QuickOrderProductPriceTransfer $quickOrderProductPriceTransfer): QuickOrderProductPriceTransfer
    {
        return $this->quickOrderClient->getQuickOrderProductPrice($quickOrderProductPriceTransfer);
    }
}
