<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductImageWidget\Plugin\ProductDetailPage;

use Spryker\Yves\Kernel\Controller\View;
use Spryker\Yves\Kernel\Controller\Widget;
use Spryker\Yves\Kernel\Plugin\AbstractWidgetBuilderPlugin;
use Symfony\Component\HttpFoundation\Request;

class ProductImageWidgetBuilderPlugin extends AbstractWidgetBuilderPlugin
{

    const NAME = 'productImage';
    const TEMPLATE = '@ProductImageWidget/_product-detail-page/images.twig';

    /**
     * @param \Spryker\Yves\Kernel\Controller\View $view
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\Controller\Widget
     */
    public function buildWidget(View $view, Request $request)
    {
        return new Widget(static::NAME, static::TEMPLATE);
    }

}
