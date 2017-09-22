<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AvailabilityWidget\Plugin\ProductDetailPage;

use Spryker\Yves\Kernel\Controller\View;
use Spryker\Yves\Kernel\Controller\Widget;
use Spryker\Yves\Kernel\Plugin\AbstractWidgetBuilderPlugin;
use Symfony\Component\HttpFoundation\Request;

class AvailabilityWidgetBuilderPlugin extends AbstractWidgetBuilderPlugin
{

    const NAME = 'addToCartButton';
    const TEMPLATE = '@AvailabilityWidget/_product-detail-page/availability.twig';

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
