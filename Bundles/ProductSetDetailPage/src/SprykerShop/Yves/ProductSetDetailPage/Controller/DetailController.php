<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSetDetailPage\Controller;

use Generated\Shared\Transfer\ProductSetDataStorageTransfer;
use SprykerShop\Yves\ProductSetDetailPage\Exception\ProductSetAccessDeniedException;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\ProductSetDetailPage\ProductSetDetailPageFactory getFactory()
 */
class DetailController extends AbstractController
{
    /**
     * @var string
     */
    public const PARAM_ATTRIBUTE = 'attributes';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PRODUCT_ACCESS_DENIED = 'product.access.denied';

    /**
     * @param \Generated\Shared\Transfer\ProductSetDataStorageTransfer $productSetDataStorageTransfer
     * @param array<\Generated\Shared\Transfer\ProductViewTransfer> $productViewTransfers
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction(ProductSetDataStorageTransfer $productSetDataStorageTransfer, array $productViewTransfers, Request $request)
    {
        $this->assertProductRestrictions($productSetDataStorageTransfer, $productViewTransfers);

        $data = [
            'productSet' => $productSetDataStorageTransfer,
            'productViews' => $productViewTransfers,
            'optionResetUrls' => $this->getFactory()
                ->getProductStorageClient()
                ->generateProductAttributesResetUrlQueryParameters($request, $productViewTransfers),
        ];

        return $this->view(
            $data,
            $this->getFactory()->getProductSetDetailPageWidgetPlugins(),
            '@ProductSetDetailPage/views/set-detail/set-detail.twig',
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetDataStorageTransfer $productSetDataStorageTransfer
     * @param array $productViewTransfers
     *
     * @throws \SprykerShop\Yves\ProductSetDetailPage\Exception\ProductSetAccessDeniedException
     *
     * @return void
     */
    protected function assertProductRestrictions(ProductSetDataStorageTransfer $productSetDataStorageTransfer, array $productViewTransfers): void
    {
        if (!$productSetDataStorageTransfer->getProductAbstractIds()) {
            return;
        }

        // Abstract IDs are available, but all are restricted
        if ($productViewTransfers) {
            return;
        }

        throw new ProductSetAccessDeniedException(static::GLOSSARY_KEY_PRODUCT_ACCESS_DENIED);
    }
}
