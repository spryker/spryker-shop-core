<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidget\Plugin\StepEngine;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\StepEngineExtension\Dependency\Plugin\StepEnginePreRenderPluginInterface;

class MerchantCheckoutStepEnginePreRenderPlugin implements StepEnginePreRenderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Sets ShipmentTransfer.merchantReference.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $itemTransfer->getShipment()->setMerchantReference($itemTransfer->getMerchantReference());
        }

        return $quoteTransfer;
    }
}
