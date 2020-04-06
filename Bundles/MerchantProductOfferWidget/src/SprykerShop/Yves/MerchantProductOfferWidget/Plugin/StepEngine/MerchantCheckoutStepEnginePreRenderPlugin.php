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
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function execute(AbstractTransfer $dataTransfer): AbstractTransfer
    {
        if (!$dataTransfer instanceof QuoteTransfer) {
            return $dataTransfer;
        }

        foreach ($dataTransfer->getItems() as $itemTransfer) {
            $itemTransfer->getShipment()->setMerchantReference($itemTransfer->getMerchantReference());
        }

        return $dataTransfer;
    }
}
