<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductDiscontinuedWidget\Plugin\ShoppingListPage;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ProductDiscontinuedWidget\Widget\ProductDiscontinuedWidget;

/**
 * @deprecated Use \SprykerShop\Yves\ProductDiscontinuedWidget\Widget\ProductDiscontinuedWidget instead.
 *
 * @method \SprykerShop\Yves\ProductDiscontinuedWidget\ProductDiscontinuedWidgetFactory getFactory()
 */
class ProductDiscontinuedWidgetPlugin extends AbstractWidgetPlugin
{
    /**
     * @param string $sku
     *
     * @return void
     */
    public function initialize(string $sku): void
    {
        $widget = new ProductDiscontinuedWidget($sku);

        $this->parameters = $widget->getParameters();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public static function getName(): string
    {
        return 'ProductDiscontinuedWidgetPlugin';
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return ProductDiscontinuedWidget::getTemplate();
    }
}
