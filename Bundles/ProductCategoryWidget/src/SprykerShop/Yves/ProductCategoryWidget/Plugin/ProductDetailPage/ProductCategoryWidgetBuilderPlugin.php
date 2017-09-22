<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductCategoryWidget\Plugin\ProductDetailPage;

use Spryker\Yves\Kernel\Controller\View;
use Spryker\Yves\Kernel\Controller\Widget;
use Spryker\Yves\Kernel\Plugin\AbstractWidgetBuilderPlugin;
use Symfony\Component\HttpFoundation\Request;

class ProductCategoryWidgetBuilderPlugin extends AbstractWidgetBuilderPlugin
{

    const NAME = 'breadcrumb';
    const TEMPLATE = '@ProductCategoryWidget/_product-detail-page/breadcrumb.twig';

    /**
     * @param \Spryker\Yves\Kernel\Controller\View $view
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\Controller\Widget
     */
    public function buildWidget(View $view, Request $request)
    {
        // TODO: replace array data to explicit transfer
        return new Widget(static::NAME, static::TEMPLATE, $this->getData($view, $request));
    }

    /**
     * @param \Spryker\Yves\Kernel\Controller\View $view
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function getData(View $view, Request $request)
    {
        return [
            'productCategories' => $this->getStorageProductTransfer($view)->getCategories(),
        ];
    }

    /**
     * @param \Spryker\Yves\Kernel\Controller\View $view
     *
     * @return \Generated\Shared\Transfer\StorageProductTransfer
     */
    protected function getStorageProductTransfer(View $view)
    {
        return $view->getData()['product']; // FIXME
    }

}
