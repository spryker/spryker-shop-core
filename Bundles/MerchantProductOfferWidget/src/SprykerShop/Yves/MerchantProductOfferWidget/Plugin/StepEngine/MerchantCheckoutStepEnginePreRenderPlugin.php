<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidget\Plugin\StepEngine;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngineExtension\Dependency\Plugin\StepEnginePreRenderPluginInterface;

class MerchantCheckoutStepEnginePreRenderPlugin implements StepEnginePreRenderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Sets ShipmentTransfer.merchantReference.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $dataTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(AbstractTransfer $dataTransfer): AbstractTransfer
    {
        if (!$dataTransfer instanceof QuoteTransfer) {
            return $dataTransfer;
        }

        foreach ($dataTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getShipment()) {
                continue;
            }

            $itemTransfer->getShipment()->setMerchantReference($itemTransfer->getMerchantReference());
        }

        return $dataTransfer;
    }
}
