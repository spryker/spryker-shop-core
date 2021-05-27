<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductReviewWidget\Plugin\ProductGroupWidget;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\ProductGroupWidgetExtension\Dependency\Plugin\ProductViewExpanderPluginInterface;

/**
 * @method \SprykerShop\Yves\ProductReviewWidget\ProductReviewWidgetFactory getFactory()
 */
class ProductReviewSummaryProductViewExpanderPlugin extends AbstractPlugin implements ProductViewExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     *  - Expands product view data transfer object with the product review summary data (average rating).
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expand(ProductViewTransfer $productViewTransfer): ProductViewTransfer
    {
        $params = $this->getFactory()->getApplicationRequest()->query->all();
        $productReviewSearchRequestTransfer = $this->getFactory()
            ->createProductReviewSearchRequestBuilder()
            ->createProductReviewSearchRequestTransfer(
                $productViewTransfer->getIdProductAbstract(),
                $this->getFactory()->getApplicationRequest()->query->all()
            );

        return $this->getFactory()
            ->getProductReviewClient()
            ->expandProductViewWithProductReviewData($productViewTransfer, $productReviewSearchRequestTransfer);
    }
}
