<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductOptionWidget\Plugin\ProductDetailPage;

use Spryker\Yves\Kernel\Controller\View;
use Spryker\Yves\Kernel\Controller\Widget;
use Spryker\Yves\Kernel\Plugin\AbstractWidgetBuilderPlugin;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\ProductOptionWidget\ProductOptionWidgetFactory getFactory()
 */
class ProductOptionWidgetBuilderPlugin extends AbstractWidgetBuilderPlugin
{

    const NAME = 'productOptions';
    const TEMPLATE = '@ProductOptionWidget/_product-detail-page/product-options.twig';

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
        $storageProductTransfer = $this->getStorageProductTransfer($view);
        $productOptionGroupsTransfer = $this
            ->getFactory()
            ->getProductOptionClient()
            ->getProductOptions($storageProductTransfer->getIdProductAbstract(), $this->getLocale());

        return [
            'productOptionGroups' => $productOptionGroupsTransfer,
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
