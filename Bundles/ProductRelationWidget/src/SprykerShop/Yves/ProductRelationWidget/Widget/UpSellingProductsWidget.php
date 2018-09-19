<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductRelationWidget\Widget;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ProductRelationWidget\ProductRelationWidgetFactory getFactory()
 */
class UpSellingProductsWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function __construct(QuoteTransfer $quoteTransfer)
    {
        $this
            ->addParameter('quote', $quoteTransfer)
            ->addParameter('productCollection', $this->findUpSellingProducts($quoteTransfer))
            ->addWidgets($this->getFactory()->getCartPageUpSellingProductsWidgetPlugins());
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'UpSellingProductsWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductRelationWidget/views/cart-similar-products-carousel/cart-similar-products-carousel.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    protected function findUpSellingProducts(QuoteTransfer $quoteTransfer)
    {
        return $this->getFactory()
            ->getProductRelationStorageClient()
            ->findUpSellingProducts($quoteTransfer, $this->getLocale());
    }
}
