<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSetDetailPage\Controller;

use Generated\Shared\Transfer\ProductSetDataStorageTransfer;
use SprykerShop\Yves\ProductSetDetailPage\Exception\ProductSetAccessDeniedException;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;

/**
 * @method \SprykerShop\Yves\ProductSetDetailPage\ProductSetDetailPageFactory getFactory()
 */
class DetailController extends AbstractController
{
    public const PARAM_ATTRIBUTE = 'attributes';
    protected const GLOSSARY_KEY_PRODUCT_RESTRICTED = 'product.access.denied';

    /**
     * @param \Generated\Shared\Transfer\ProductSetDataStorageTransfer $productSetDataStorageTransfer
     * @param \Generated\Shared\Transfer\ProductViewTransfer[] $productViewTransfers
     *
     * @throws \SprykerShop\Yves\ProductSetDetailPage\Exception\ProductSetAccessDeniedException
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction(ProductSetDataStorageTransfer $productSetDataStorageTransfer, array $productViewTransfers)
    {
        if ($this->areProductsRestricted($productSetDataStorageTransfer, $productViewTransfers)) {
            throw new ProductSetAccessDeniedException(static::GLOSSARY_KEY_PRODUCT_RESTRICTED);
        }

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

    /**
     * @param \Generated\Shared\Transfer\ProductSetDataStorageTransfer $productSetDataStorageTransfer
     * @param array $productViewTransfers
     *
     * @return bool
     */
    protected function areProductsRestricted(ProductSetDataStorageTransfer $productSetDataStorageTransfer, array $productViewTransfers): bool
    {
        return !empty($productSetDataStorageTransfer->getProductAbstractIds()) &&
            empty($productViewTransfers);
    }
}
