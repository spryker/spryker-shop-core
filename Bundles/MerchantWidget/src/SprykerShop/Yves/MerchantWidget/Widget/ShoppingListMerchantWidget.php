<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantWidget\Widget;

use Generated\Shared\Transfer\MerchantStorageCriteriaTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\MerchantWidget\MerchantWidgetFactory getFactory()
 */
class ShoppingListMerchantWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_IS_MERCHANT_ACTIVE = 'isMerchantActive';

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     */
    public function __construct(ProductViewTransfer $productViewTransfer)
    {
        $this->addIsMerchantActiveParameter($productViewTransfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ShoppingListMerchantWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@MerchantWidget/views/shopping-list/merchant.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return $this
     */
    protected function addIsMerchantActiveParameter(ProductViewTransfer $productViewTransfer)
    {
        if (!$productViewTransfer->getMerchantReference()) {
            $this->addParameter(static::PARAMETER_IS_MERCHANT_ACTIVE, null);

            return $this;
        }

        $merchantStorageTransfer = $this->getFactory()
            ->getMerchantStorageClient()
            ->findOne(
                (new MerchantStorageCriteriaTransfer())
                    ->addMerchantReference($productViewTransfer->getMerchantReference()),
            );

        $this->addParameter(static::PARAMETER_IS_MERCHANT_ACTIVE, $merchantStorageTransfer !== null);

        return $this;
    }
}
