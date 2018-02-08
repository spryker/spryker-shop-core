<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductDetailPage\Controller;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Shared\Storage\StorageConstants;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Client\Product\ProductClientInterface getClient()
 * @method \SprykerShop\Yves\ProductDetailPage\ProductDetailPageFactory getFactory()
 */
class ProductController extends AbstractController
{
    const ATTRIBUTE_PRODUCT_DATA = 'productData';

    const PARAM_ATTRIBUTE = 'attribute';

    const STORAGE_CACHE_STRATEGY = StorageConstants::STORAGE_CACHE_STRATEGY_INCREMENTAL;

    /**
     * @param array $productData
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function detailAction(array $productData, Request $request)
    {
        $productViewTransfer = $this->getFactory()
            ->getProductStorageClient()
            ->mapProductStorageData($productData, $this->getLocale(), $this->getSelectedAttributes($request));

        $data = [
            'product' => $productViewTransfer,
            'productUrl' => $this->getProductUrl($productViewTransfer),
        ];

        return $this->view(
            $data, 
            $this->getFactory()->getProductDetailPageWidgetPlugins(),
            '@ProductDetailPage/views/pdp/pdp.twig'
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return string
     */
    protected function getProductUrl(ProductViewTransfer $productViewTransfer)
    {
        if (!$productViewTransfer->getIdProductConcrete()) {
            return $productViewTransfer->getUrl();
        }

        $variantUriParams[static::PARAM_ATTRIBUTE] = $productViewTransfer->getSelectedAttributes();

        return sprintf(
            '%s?%s',
            $productViewTransfer->getUrl(),
            http_build_query($variantUriParams)
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function getSelectedAttributes(Request $request)
    {
        return array_filter($request->query->get(self::PARAM_ATTRIBUTE, []));
    }
}
