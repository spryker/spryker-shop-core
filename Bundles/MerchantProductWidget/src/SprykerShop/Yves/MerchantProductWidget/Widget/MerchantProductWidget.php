<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductWidget\Widget;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\MerchantProductWidget\MerchantProductWidgetFactory getFactory()
 */
class MerchantProductWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param bool $showRadioButton
     * @param bool $checked
     */
    public function __construct(
        ProductViewTransfer $productViewTransfer,
        bool $showRadioButton = false,
        bool $checked = true
    ) {
        $this->addMerchantProductViewParameter($productViewTransfer);
        $this->addProductViewParameter($productViewTransfer);
        $this->addshowRadioButton($showRadioButton);
        $this->addChecked($checked);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'MerchantProductWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@MerchantProductWidget/views/merchant-product-widget/merchant-product-widget.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return void
     */
    protected function addMerchantProductViewParameter(ProductViewTransfer $productViewTransfer): void
    {
        $this->addParameter(
            'merchantProductView',
            $this->getFactory()->createMerchantProductReader()->findMerchantProductView($productViewTransfer, $this->getLocale())
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return void
     */
    protected function addProductViewParameter(ProductViewTransfer $productViewTransfer): void
    {
        $this->addParameter('productView', $productViewTransfer);
    }

    /**
     * @param bool $showRadioButton
     *
     * @return void
     */
    protected function addshowRadioButton(bool $showRadioButton): void
    {
        $this->addParameter('showRadioButton', $showRadioButton);
    }

    /**
     * @param bool $checked
     *
     * @return void
     */
    protected function addChecked(bool $checked): void
    {
        $this->addParameter('checked', $checked);
    }
}
