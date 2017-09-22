<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductDetailPage\Controller;

use Pyz\Yves\Application\Controller\AbstractController;
use Spryker\Shared\Storage\StorageConstants;
use Spryker\Yves\Kernel\Controller\View;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Client\Product\ProductClientInterface getClient()
 * @method \SprykerShop\Yves\ProductDetailPage\ProductDetailPageFactory getFactory()
 */
class ProductController extends AbstractController
{

    const ATTRIBUTE_PRODUCT_DATA = 'productData';
    const ATTRIBUTE_STORAGE_PRODUCT_TRANSFER = 'storageProductTransfer';

    const PARAM_ATTRIBUTE = 'attribute';

    const STORAGE_CACHE_STRATEGY = StorageConstants::STORAGE_CACHE_STRATEGY_INCREMENTAL;

    /**
     * @param array $productData
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\Controller\View
     */
    public function detailAction(array $productData, Request $request)
    {
        $storageProductTransfer = $this->getFactory()
            ->createStorageProductMapper()
            ->mapStorageProduct($productData, $request, $this->getSelectedAttributes($request));

        // TODO: use transfer instead of array as data
        $view = new View([
            'product' => $storageProductTransfer,
            'page_keywords' => $storageProductTransfer->getMetaKeywords(),
            'page_description' => $storageProductTransfer->getMetaDescription(),
        ]);

        $view->buildWidgets($this->getFactory()->getProductDetailPageWidgetBuilderPlugins(), $request);

        return $view;
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
