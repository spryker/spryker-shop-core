<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidget\Widget;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\MerchantProductOfferWidget\MerchantProductOfferWidgetFactory getFactory()
 */
class MerchantProductOfferWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     */
    public function __construct(ProductViewTransfer $productViewTransfer)
    {
        $this->addProductOfferCollection($productViewTransfer);
        $this->addProductViewParameter($productViewTransfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'MerchantProductOfferWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@MerchantProductOfferWidget/views/merchant-product-offer-widget/merchant-product-offer-widget.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return void
     */
    protected function addProductOfferCollection(ProductViewTransfer $productViewTransfer): void
    {
        $this->addParameter(
            'merchantProductViews',
            $this->getFactory()
                ->createProductOfferReader()
                ->getMerchantProductViewCollection($productViewTransfer, $this->getLocale())
                ->getMerchantProductViews()
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
}
