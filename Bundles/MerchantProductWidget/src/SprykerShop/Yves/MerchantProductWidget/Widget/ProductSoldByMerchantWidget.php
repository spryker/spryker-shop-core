<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductWidget\Widget;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\MerchantProductWidget\MerchantProductOfferWidgetFactory getFactory()
 */
class ProductSoldByMerchantWidget extends AbstractWidget
{
    protected const PARAMETER_MERCHANT = 'merchant';

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     */
    public function __construct(ItemTransfer $itemTransfer)
    {
        $this->addMerchantParameter($itemTransfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ProductSoldByMerchantWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@MerchantProductWidget/views/product-sold-by-merchant/product-sold-by-merchant.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return $this
     */
    protected function addMerchantParameter(ItemTransfer $itemTransfer)
    {
        $merchantStorageTransfer = null;

        if ($itemTransfer->getMerchantReference()) {
            $merchantStorageTransfer = $this->getFactory()
                ->getMerchantStorageClient()
                ->findOneByMerchantReference($itemTransfer->getMerchantReference());
        }

        $this->addParameter(static::PARAMETER_MERCHANT, $merchantStorageTransfer);

        return $this;
    }
}
