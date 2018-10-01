<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSetDetailPage\Controller;

use Generated\Shared\Transfer\ProductSetDataStorageTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;

/**
 * @method \SprykerShop\Yves\ProductSetDetailPage\ProductSetDetailPageFactory getFactory()
 */
class DetailController extends AbstractController
{
    public const PARAM_ATTRIBUTE = 'attributes';

    /**
     * @param \Generated\Shared\Transfer\ProductSetDataStorageTransfer $productSetDataStorageTransfer
     * @param \Generated\Shared\Transfer\ProductViewTransfer[] $productViewTransfers
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction(ProductSetDataStorageTransfer $productSetDataStorageTransfer, array $productViewTransfers)
    {
        $data = [
            'productSet' => $productSetDataStorageTransfer,
            'productViews' => $productViewTransfers,
        ];

        return $this->view(
            $data,
            $this->getFactory()->getProductSetDetailPageWidgetPlugins(),
            '@ProductSetDetailPage/views/set-detail/set-detail.twig'
        );
    }
}
