<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductDetailPage\Controller;

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
        $storageProductTransfer = $this->getFactory()
            ->getProductClient()
            ->mapStorageProductForCurrentLocale($productData, $this->getSelectedAttributes($request));

        $data = [
            'product' => $storageProductTransfer,
        ];

        return $this->view($data, $this->getFactory()->getProductDetailPageWidgetPlugins());
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
